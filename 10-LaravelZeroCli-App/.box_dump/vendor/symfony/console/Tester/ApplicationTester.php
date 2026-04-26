<?php










namespace Symfony\Component\Console\Tester;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;











class ApplicationTester
{
use TesterTrait;

public function __construct(
private Application $application,
) {
}













public function run(array $input, array $options = []): int
{
$this->input = new ArrayInput($input);
if (isset($options['interactive'])) {
$this->input->setInteractive($options['interactive']);
}

if ($this->inputs) {
$this->input->setStream(self::createStream($this->inputs));
}

$this->initOutput($options);



$prevShellVerbosity = [getenv('SHELL_VERBOSITY'), $_ENV['SHELL_VERBOSITY'] ?? false, $_SERVER['SHELL_VERBOSITY'] ?? false];
if (\function_exists('putenv')) {
@putenv('SHELL_VERBOSITY');
}
unset($_ENV['SHELL_VERBOSITY'], $_SERVER['SHELL_VERBOSITY']);

try {
return $this->statusCode = $this->application->run($this->input, $this->output);
} finally {
if (false !== $prevShellVerbosity[0]) {
if (\function_exists('putenv')) {
@putenv('SHELL_VERBOSITY='.$prevShellVerbosity[0]);
}
}
if (false !== $prevShellVerbosity[1]) {
$_ENV['SHELL_VERBOSITY'] = $prevShellVerbosity[1];
}
if (false !== $prevShellVerbosity[2]) {
$_SERVER['SHELL_VERBOSITY'] = $prevShellVerbosity[2];
}
}
}
}
