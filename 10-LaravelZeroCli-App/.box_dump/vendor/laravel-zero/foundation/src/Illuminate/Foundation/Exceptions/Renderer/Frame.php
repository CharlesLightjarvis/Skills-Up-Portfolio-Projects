<?php

namespace Illuminate\Foundation\Exceptions\Renderer;

use Illuminate\Foundation\Concerns\ResolvesDumpSource;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

use function Illuminate\Filesystem\join_paths;

class Frame
{
use ResolvesDumpSource;






protected $exception;






protected $classMap;






protected $frame;






protected $basePath;






protected $previous;






protected $isMain = false;










public function __construct(FlattenException $exception, array $classMap, array $frame, string $basePath, ?Frame $previous = null)
{
$this->exception = $exception;
$this->classMap = $classMap;
$this->frame = $frame;
$this->basePath = $basePath;
$this->previous = $previous;
}






public function source()
{
return match (true) {
is_string($this->class()) => $this->class(),
default => $this->file(),
};
}






public function editorHref()
{
return $this->resolveSourceHref($this->frame['file'], $this->line());
}






public function class()
{
if (! empty($this->frame['class'])) {
return $this->frame['class'];
}

$class = array_search((string) realpath($this->frame['file']), $this->classMap, true);

return $class === false ? null : $class;
}






public function file()
{
return match (true) {
! isset($this->frame['file']) => '[internal function]',
! is_string($this->frame['file']) => '[unknown file]',
default => str_replace($this->basePath.DIRECTORY_SEPARATOR, '', $this->frame['file']),
};
}






public function line()
{
if (! is_file($this->frame['file']) || ! is_readable($this->frame['file'])) {
return 0;
}

$maxLines = count(file($this->frame['file']) ?: []);

return $this->frame['line'] > $maxLines ? 1 : $this->frame['line'];
}






public function operator()
{
return $this->frame['type'] ?? '';
}






public function callable()
{
return match (true) {
! empty($this->frame['function']) => $this->frame['function'],
default => 'throw',
};
}






public function args()
{
if (! isset($this->frame['args']) || ! is_array($this->frame['args']) || count($this->frame['args']) === 0) {
return [];
}

return array_map(function ($argument) {
[$key, $value] = $argument;

return match ($key) {
'object' => "{$key}({$value})",
default => $key,
};
}, $this->frame['args']);
}






public function snippet()
{
if (! is_file($this->frame['file']) || ! is_readable($this->frame['file'])) {
return '';
}

$contents = file($this->frame['file']) ?: [];

$start = max($this->line() - 6, 0);

$length = 8 * 2 + 1;

return implode('', array_slice($contents, $start, $length));
}






public function isFromVendor()
{
return ! str_starts_with($this->frame['file'], $this->basePath)
|| str_starts_with($this->frame['file'], join_paths($this->basePath, 'vendor'));
}






public function previous()
{
return $this->previous;
}






public function markAsMain()
{
$this->isMain = true;
}






public function isMain()
{
return $this->isMain;
}
}
