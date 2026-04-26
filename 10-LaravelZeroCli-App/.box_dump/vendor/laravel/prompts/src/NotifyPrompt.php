<?php

namespace Laravel\Prompts;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class NotifyPrompt extends Prompt
{



public function __construct(
public string $title,
public string $body = '',
public string $subtitle = '',
public string $sound = '',
public string $icon = '',
) {

}




public function prompt(): bool
{
return match (PHP_OS_FAMILY) {
'Darwin' => $this->sendMacOS(),
'Linux' => $this->sendLinux(),
default => false,
};
}




protected function sendMacOS(): bool
{
$script = 'display notification '.$this->escapeAppleScript($this->body);
$script .= ' with title '.$this->escapeAppleScript($this->title);

if ($this->subtitle !== '') {
$script .= ' subtitle '.$this->escapeAppleScript($this->subtitle);
}

if ($this->sound !== '') {
$script .= ' sound name '.$this->escapeAppleScript($this->sound);
}

return $this->execute(['osascript', '-e', $script]);
}




protected function sendLinux(): bool
{
$finder = new ExecutableFinder;

if ($finder->find('notify-send') !== null) {
return $this->sendLinuxNotifySend();
}

if ($finder->find('kdialog') !== null) {
return $this->sendLinuxKDialog();
}

return false;
}




protected function sendLinuxNotifySend(): bool
{
$command = ['notify-send'];

if ($this->icon !== '') {
$command[] = '--icon';
$command[] = $this->icon;
}

$command[] = $this->title;

if ($this->body !== '') {
$command[] = $this->body;
}

return $this->execute($command);
}




protected function sendLinuxKDialog(): bool
{
$message = $this->body !== '' ? "{$this->title}: {$this->body}" : $this->title;

return $this->execute(['kdialog', '--passivepopup', $message, '5', '--title', $this->title]);
}






protected function execute(array $command): bool
{
$process = new Process($command);
$process->run();

return $process->isSuccessful();
}




protected function escapeAppleScript(string $value): string
{
return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $value).'"';
}




public function display(): void
{
$this->prompt();
}




public function value(): bool
{
return true;
}
}
