<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateGitHubRepo implements Tool
{
    public function description(): Stringable|string
    {
        return 'Create a new GitHub repository and push a local project to it. Specify the directory where the project lives.';
    }

    public function handle(Request $request): Stringable|string
    {
        $name = $request['name'];
        $description = $request['description'] ?? '';
        $directory = $request['directory'] ?? '/home/forge/projects/' . $name;
        $private = $request['private'] ?? true;

        $token = config('services.github.token');

        if (! $token) {
            return 'GitHub token not configured. Set GITHUB_TOKEN in your .env file.';
        }

        // Get the authenticated GitHub username for this token
        $username = Http::withToken($token)->get('https://api.github.com/user')->json('login');

        if (! is_dir($directory)) {
            return "Directory not found: {$directory}. Create the project first.";
        }

        // Create the repository via GitHub API
        $response = Http::withToken($token)->post('https://api.github.com/user/repos', [
            'name' => $name,
            'description' => $description,
            'private' => $private,
            'auto_init' => false,
        ]);

        if ($response->status() === 422) {
            // Repo might already exist, try to use it
            $repoUrl = "https://{$token}@github.com/{$username}/{$name}.git";
        } elseif (! $response->successful()) {
            return 'Failed to create repository: ' . $response->json('message', 'Unknown error');
        } else {
            $cloneUrl = $response->json('clone_url');
            $repoUrl = str_replace('https://', "https://{$token}@", $cloneUrl);
        }

        $fullName = $response->json('full_name', $name);

        // Chain all git commands in a single shell execution
        $script = implode(' && ', [
            "cd {$directory}",
            'git init',
            'git add -A',
            'git commit -m "Initial commit" --allow-empty',
            "git remote remove origin 2>/dev/null; git remote add origin {$repoUrl}",
            'git branch -M main',
            'git push -u origin main --force',
        ]);

        $output = [];
        $exitCode = 0;
        exec("{$script} 2>&1", $output, $exitCode);

        $result = implode("\n", $output);

        if ($exitCode !== 0) {
            return "Repository created but push failed:\n{$result}";
        }

        return "Repository created and code pushed: https://github.com/{$username}/{$name}";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->required(),
            'description' => $schema->string(),
            'directory' => $schema->string(),
            'private' => $schema->boolean(),
        ];
    }
}
