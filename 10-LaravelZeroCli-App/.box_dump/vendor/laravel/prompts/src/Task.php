<?php

namespace Laravel\Prompts;

use Closure;
use Laravel\Prompts\Support\Logger;
use Laravel\Prompts\Themes\Default\Concerns\InteractsWithStrings;
use RuntimeException;

class Task extends Prompt
{
use InteractsWithStrings;




protected int $minWidth = 0;




public int $interval = 100;




public int $count = 0;




public bool $static = false;




protected int $pid;






protected $socket;






public array $logs = [];






public array $stableMessages = [];




public int $maxStableMessages = 10;




public string $identifier = '';




public bool $finished = false;




protected string $buffer = '';




protected ?int $partialStartIndex = null;




public function __construct(
public string $label = '',
public int $limit = 10,
public bool $keepSummary = false,
public ?string $subLabel = null,
) {
$this->identifier = uniqid();
}

/**
@template





*/
public function run(Closure $callback): mixed
{
$this->limit = min($this->limit, $this->terminal()->lines() - 10);
$this->recalculateMaxStableMessages();

$this->capturePreviousNewLines();

if (! function_exists('pcntl_fork')) {
return $this->renderStatically($callback);
}

$originalAsync = pcntl_async_signals(true);

pcntl_signal(SIGINT, fn () => exit());

try {
$this->hideCursor();
$this->render();

$sockets = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

if ($sockets === false) {
return $this->renderStatically($callback);
}

$this->pid = pcntl_fork();

if ($this->pid === 0) {
fclose($sockets[1]);
$childSocket = $sockets[0];
stream_set_blocking($childSocket, false);

while (true) { 
$this->receiveMessages($childSocket);

if (! $this->finished) {
$this->render();
$this->count++;
}

usleep($this->interval * 1000);
}
} else {
fclose($sockets[0]);
$this->socket = $sockets[1];

$logger = new Logger($this->identifier, $this->socket);
$result = $callback($logger);

if ($this->socket !== null) {

fwrite($this->socket, $this->identifier.'_'.'reset:'.($originalAsync ? 1 : 0).PHP_EOL);
usleep($this->interval * 2000);
}

return $result;
}
} catch (\Throwable $e) {
$this->resetTerminal($originalAsync);

throw $e;
}
}






protected function receiveMessages($socket): void
{
$prefix = preg_quote($this->identifier, '/');

while (($data = fgets($socket)) !== false) {

if (! str_ends_with($data, PHP_EOL)) {
$this->buffer .= $data;

continue;
}

$line = rtrim($this->buffer.$data, PHP_EOL);
$this->buffer = '';

if ($line === '') {
continue;
}


if (preg_match('/^'.$prefix.'_(success|warning|error|label|sublabel|reset|partial|commitpartial):(.*)/', $line, $matches)) {
$type = $matches[1];
$content = $matches[2];

if ($type === 'reset') {
$this->resetTerminal((bool) $content);

continue;
}

if ($type === 'partial') {
$this->replacePartialLines($content);

continue;
}

if ($type === 'commitpartial') {
$this->partialStartIndex = null;

continue;
}

if ($type === 'label') {
$this->label = $content;
} elseif ($type === 'sublabel') {
$this->subLabel = $content;
$this->recalculateMaxStableMessages();
} else {
$this->stableMessages[] = ['type' => $type, 'message' => $content];
$this->logs = [];
$this->partialStartIndex = null;
}

while (count($this->stableMessages) > $this->maxStableMessages) {
array_shift($this->stableMessages);
}

continue;
}


$line = preg_replace('/\e\[(?:1)?G\e\[2K/', '', $line);


$this->addLogLines($line);
}
}




protected function addLogLines(string $line): void
{
$width = $this->terminal()->cols() - 10;
$plainText = $this->stripEscapeSequences($line);

if (mb_strwidth($plainText) > $width) {
$wrapped = $this->ansiWordwrap($line, $width);
} else {
$wrapped = [$line];
}

array_push($this->logs, ...$wrapped);

while (count($this->logs) > $this->limit) {
array_shift($this->logs);
}
}




protected function replacePartialLines(string $text): void
{
if ($this->partialStartIndex === null) {
$this->partialStartIndex = count($this->logs);
}


$this->logs = array_slice($this->logs, 0, $this->partialStartIndex);


$width = $this->terminal()->cols() - 10;
$plainText = $this->stripEscapeSequences($text);

if (mb_strwidth($plainText) > $width) {
$wrapped = $this->ansiWordwrap($text, $width);
} else {
$wrapped = [$text];
}

array_push($this->logs, ...$wrapped);

while (count($this->logs) > $this->limit) {
array_shift($this->logs);
$this->partialStartIndex = max(0, $this->partialStartIndex - 1);
}
}




protected function recalculateMaxStableMessages(): void
{
$reserved = 2 + ($this->subLabel !== null && $this->subLabel !== '' ? 1 : 0);
$this->maxStableMessages = max(0, $this->terminal()->lines() - 10 - $this->limit - $reserved);
}




protected function resetTerminal(bool $originalAsync): void
{
$this->finished = true;

pcntl_async_signals($originalAsync);
pcntl_signal(SIGINT, SIG_DFL);

if ($this->socket !== null) {
fclose($this->socket);
$this->socket = null;
}

if ($this->keepSummary && count($this->stableMessages) > 0) {
$this->render();

return;
}

$this->eraseRenderedLines();
}

/**
@template





*/
protected function renderStatically(Closure $callback): mixed
{
$this->static = true;

try {
$this->hideCursor();
$this->render();

$logger = new Logger($this->identifier);
$result = $callback($logger);
} finally {
$this->eraseRenderedLines();
}

return $result;
}






public function prompt(): never
{
throw new RuntimeException('Task cannot be prompted.');
}




public function value(): bool
{
return true;
}




protected function eraseRenderedLines(): void
{
$lines = explode(PHP_EOL, $this->prevFrame);
$this->moveCursor(-999, -count($lines) + 1);
$this->eraseDown();
}




public function __destruct()
{
if (! empty($this->pid)) {
posix_kill($this->pid, SIGHUP);
}

parent::__destruct();
}
}
