<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class WriteFile implements Tool
{
    public function description(): Stringable|string
    {
        return 'Write content to a file. By default writes relative to the app. Use the directory parameter to write to a different project.';
    }

    public function handle(Request $request): Stringable|string
    {
        $directory = $request['directory'] ?? base_path();
        $path = $directory . '/' . ltrim($request['path'], '/');

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $request['content']);

        return "File written: {$request['path']}";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'path' => $schema->string()->required(),
            'content' => $schema->string()->required(),
            'directory' => $schema->string(),
        ];
    }
}
