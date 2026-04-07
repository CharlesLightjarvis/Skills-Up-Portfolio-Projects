<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ReadFile implements Tool
{
    public function description(): Stringable|string
    {
        return 'Read the contents of a file. By default reads relative to the app. Use the directory parameter to read from a different project.';
    }

    public function handle(Request $request): Stringable|string
    {
        $directory = $request['directory'] ?? base_path();
        $path = $directory . '/' . ltrim($request['path'], '/');

        if (! file_exists($path)) {
            return "File not found: {$request['path']}";
        }

        return file_get_contents($path);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'path' => $schema->string()->required(),
            'directory' => $schema->string(),
        ];
    }
}
