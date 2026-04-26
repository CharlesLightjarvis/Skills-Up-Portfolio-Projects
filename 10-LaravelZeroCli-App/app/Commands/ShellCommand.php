<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\StringInput;

class ShellCommand extends Command
{
    protected $signature = 'shell';

    protected $description = 'Start interactive shell mode';

    public function handle(): int
    {
        $version = config('app.version', '0.1');

        $this->newLine();
        $this->line("  <fg=blue;options=bold>Product CLI</> <fg=gray>v{$version}</>");
        $this->line('  Type a command or <fg=yellow>help</>. Press <fg=yellow>Ctrl+C</> or type <fg=yellow>exit</> to quit.');
        $this->newLine();

        // Get the raw underlying output so Termwind can render to it correctly.
        // When we call getApplication()->run() with an OutputStyle that's already
        // resolved, Symfony reuses it directly without going through the container,
        // so TermwindServiceProvider's resolving() callback never fires.
        // Calling renderUsing() manually here ensures render() stays visible.
        $rawOutput = method_exists($this->output, 'getOutput')
            ? $this->output->getOutput()
            : $this->output;

        while (true) {
            try {
                $raw = $this->ask('  <fg=green>></> ');
            } catch (\Throwable) {
                break;
            }

            if ($raw === null) {
                break;
            }

            $raw = trim($raw);

            if ($raw === '') {
                continue;
            }

            if (in_array($raw, ['exit', 'quit', 'q'], true)) {
                break;
            }

            if (function_exists('\Termwind\renderUsing')) {
                \Termwind\renderUsing($rawOutput);
            }

            try {
                $this->getApplication()->run(
                    new StringInput($raw),
                    $this->output
                );
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }

            $this->newLine();
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
