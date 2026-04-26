<?php

namespace Laravel\Prompts\Support;

class Logger
{





public function __construct(protected string $identifier, protected $socket = null)
{

}




protected string $streamBuffer = '';




public function line(string $message): void
{
$this->write(rtrim($message));
}




public function partial(string $chunk): void
{
$this->streamBuffer .= $chunk;
$this->write($this->streamBuffer, 'partial');
}




public function commitPartial(): void
{
$this->streamBuffer = '';
$this->write('', 'commitpartial');
}




public function success(string $message): void
{
$this->write($message, 'success');
}




public function warning(string $message): void
{
$this->write($message, 'warning');
}




public function error(string $message): void
{
$this->write($message, 'error');
}




public function label(string $message): void
{
$this->write($message, 'label');
}




public function subLabel(string $message): void
{
$this->write($message, 'sublabel');
}




protected function write(string $message, ?string $type = null): void
{
if ($type !== null) {
fwrite($this->socket, $this->prefix($type, $message).PHP_EOL);
} else {
fwrite($this->socket, $message.PHP_EOL);
}
}




protected function prefix(string $type, string $message): string
{
return $this->identifier.'_'.$type.':'.rtrim($message, PHP_EOL);
}
}
