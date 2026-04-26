<?php

declare(strict_types=1);

namespace Pest\Profanity;

use Closure;




final class Analyser
{









public static function analyse(
array $files,
Closure $callback,
array $excludingWords = [],
array $includingWords = [],
$languages = null
): void {
foreach ($files as $file) {
$errors = ProfanityAnalyser::analyse($file, $excludingWords, $includingWords, $languages);
$callback(new Result($file, $errors));
}
}
}
