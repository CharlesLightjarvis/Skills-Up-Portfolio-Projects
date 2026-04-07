<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class RunShell implements Tool
{
    /**
     * Allowed command prefixes for safety.
     */
    private array $allowedPrefixes = [
        'composer create-project',
        'composer require',
        'composer install',
        'php artisan',
        'git init',
        'git add',
        'git commit',
        'git push',
        'git remote',
        'git status',
        'git log',
        'mkdir',
        'ls',
        'cat',
        'cloud ',
    ];

    public function description(): Stringable|string
    {
        return 'Run a shell command on the server. Only specific commands are allowed for safety: composer, php artisan, git, cloud CLI, and basic file operations. Commands run in /home/forge/projects by default — use the directory parameter to target a specific project.';
    }

    public function handle(Request $request): Stringable|string
    {
        $command = trim($request['command']);

        if (! $this->isAllowed($command)) {
            return "Command not allowed. Allowed commands: composer (create-project, require, install), php artisan, git (init, add, commit, push, remote, status, log), cloud CLI, mkdir, ls, cat.";
        }

        $workdir = $request['directory'] ?? '/home/forge/projects';

        $output = [];
        $exitCode = 0;

        exec("cd {$workdir} && {$command} 2>&1", $output, $exitCode);

        $result = implode("\n", $output);

        if ($exitCode !== 0) {
            return "Command failed (exit code {$exitCode}):\n{$result}";
        }

        return $result ?: 'Command completed successfully.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'command' => $schema->string()->required(),
            'directory' => $schema->string(),
        ];
    }

    private function isAllowed(string $command): bool
    {
        foreach ($this->allowedPrefixes as $prefix) {
            if (str_starts_with($command, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
