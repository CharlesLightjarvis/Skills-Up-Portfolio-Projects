<?php

declare(strict_types=1);

namespace Pest\Plugins\Concerns;




trait HandleArguments
{





public function hasArgument(string $argument, array $arguments): bool
{
foreach ($arguments as $arg) {
if ($arg === $argument) {
return true;
}

if (str_starts_with((string) $arg, "$argument=")) { 
return true;
}
}

return false;
}







public function pushArgument(string $argument, array $arguments): array
{
$arguments[] = $argument;

return $arguments;
}







public function popArgument(string $argument, array $arguments): array
{
$arguments = array_flip($arguments);

unset($arguments[$argument]);

return array_values(array_flip($arguments));
}






public function popArgumentValue(string $argument, array &$arguments): ?string
{
foreach ($arguments as $key => $value) {
if (str_contains($value, "$argument=")) {
unset($arguments[$key]);
$arguments = array_values($arguments);

return substr($value, strlen($argument) + 1);
}

if ($value === $argument && isset($arguments[$key + 1])) {
$result = $arguments[$key + 1];
unset($arguments[$key], $arguments[$key + 1]);
$arguments = array_values($arguments);

return $result;
}
}

return null;
}
}
