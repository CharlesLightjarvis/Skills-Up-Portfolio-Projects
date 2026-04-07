<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class DeleteProject implements Tool
{
    public function description(): Stringable|string
    {
        return 'Delete a project from the server, and optionally delete its GitHub repository and Laravel Cloud application too.';
    }

    public function handle(Request $request): Stringable|string
    {
        $name = $request['name'];
        $directory = '/home/forge/projects/' . $name;
        $deleteGithub = $request['delete_github'] ?? false;
        $deleteCloud = $request['delete_cloud'] ?? false;
        $results = [];

        // Delete local project
        if (is_dir($directory)) {
            File::deleteDirectory($directory);
            $results[] = "🗑️ Local project deleted: {$directory}";
        } else {
            $results[] = "📁 Local project not found: {$directory}";
        }

        // Delete GitHub repo
        if ($deleteGithub) {
            $token = config('services.github.token');

            if ($token) {
                $username = Http::withToken($token)->get('https://api.github.com/user')->json('login');
                $response = Http::withToken($token)->delete("https://api.github.com/repos/{$username}/{$name}");

                $results[] = $response->successful()
                    ? "🗑️ GitHub repo deleted: {$username}/{$name}"
                    : "⚠️ GitHub repo not found or couldn't delete: {$username}/{$name}";
            }
        }

        // Delete Cloud app
        if ($deleteCloud) {
            $cloudToken = config('services.laravel_cloud.token');

            if ($cloudToken) {
                // Find the app by name
                $apps = Http::withToken($cloudToken)
                    ->accept('application/json')
                    ->get('https://cloud.laravel.com/api/applications')
                    ->json('data', []);

                $app = collect($apps)->first(fn ($a) => $a['attributes']['slug'] === $name || $a['attributes']['name'] === $name);

                if ($app) {
                    Http::withToken($cloudToken)
                        ->accept('application/json')
                        ->delete("https://cloud.laravel.com/api/applications/{$app['id']}");
                    $results[] = "🗑️ Cloud app deleted: {$app['attributes']['name']}";
                } else {
                    $results[] = "⚠️ Cloud app not found: {$name}";
                }
            }
        }

        return implode("\n", $results);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->required(),
            'delete_github' => $schema->boolean(),
            'delete_cloud' => $schema->boolean(),
        ];
    }
}
