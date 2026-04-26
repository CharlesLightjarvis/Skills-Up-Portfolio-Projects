<?php

declare(strict_types=1);

namespace Pest\Profanity;




class Validator
{






public static function validateLanguages(?array $languages): array
{
if ($languages === null) {
return [];
}

$profanitiesDir = __DIR__.'/Config/profanities';
$invalidLanguages = [];

foreach ($languages as $language) {
$specificLanguage = "$profanitiesDir/$language.php";
if (! file_exists($specificLanguage)) {
$invalidLanguages[] = $language;
}
}

return $invalidLanguages;
}
}
