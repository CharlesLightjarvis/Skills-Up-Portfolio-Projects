<?php

declare(strict_types=1);

namespace Pest\Profanity\Logging;

use Pest\Profanity\Contracts\Logger;




final class JsonLogger implements Logger
{





public function __construct(
private readonly string $outputPath,
private array $logs = [],
) {

}




public function append(string $path, array $profanity): void
{
$this->logs[] = [
'file' => $path,
'profanity' => $profanity,
];
}




public function output(): void
{
$json = json_encode([
'format' => 'pest',
'result' => $this->logs,
], JSON_THROW_ON_ERROR);
file_put_contents($this->outputPath, $json);
}
}
