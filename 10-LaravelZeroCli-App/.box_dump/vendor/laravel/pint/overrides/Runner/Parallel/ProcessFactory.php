<?php

declare(strict_types=1);











namespace PhpCsFixer\Runner\Parallel;























use Illuminate\Support\ProcessUtils;
use PhpCsFixer\Runner\RunnerConfig;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

/**
@readonly








*/
final class ProcessFactory
{
public function create(
LoopInterface $loop,
InputInterface $input,
RunnerConfig $runnerConfig,
ProcessIdentifier $identifier,
int $serverPort
): Process {
$commandArgs = $this->getCommandArgs($serverPort, $identifier, $input, $runnerConfig);

return new Process(
implode(' ', $commandArgs),
$loop,
$runnerConfig->getParallelConfig()->getProcessTimeout()
);
}






public function getCommandArgs(int $serverPort, ProcessIdentifier $identifier, InputInterface $input, RunnerConfig $runnerConfig): array
{
$phpBinary = (new PhpExecutableFinder)->find(false);

if ($phpBinary === false) {
throw new ParallelisationException('Cannot find PHP executable.');
}

$mainScript = $_SERVER['argv'][0];

$commandArgs = [
ProcessUtils::escapeArgument($phpBinary),
ProcessUtils::escapeArgument($mainScript),
'worker',
'--port',
(string) $serverPort,
'--identifier',
ProcessUtils::escapeArgument($identifier->toString()),
];

if ($runnerConfig->isDryRun()) {
$commandArgs[] = '--dry-run';
}

if (filter_var($input->getOption('diff'), FILTER_VALIDATE_BOOLEAN)) {
$commandArgs[] = '--diff';
}

if (filter_var($input->getOption('stop-on-violation'), FILTER_VALIDATE_BOOLEAN)) {
$commandArgs[] = '--stop-on-violation';
}

foreach (['allow-risky', 'config', 'rules', 'using-cache', 'cache-file'] as $option) {
$optionValue = $input->getOption($option);

if ($optionValue !== null) {
$commandArgs[] = "--{$option}";
$commandArgs[] = ProcessUtils::escapeArgument($optionValue);
}
}

return $commandArgs;
}
}
