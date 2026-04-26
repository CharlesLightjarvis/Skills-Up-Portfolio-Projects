<?php

(static function (): void {
if (file_exists($autoload = __DIR__ . '/../../../autoload.php')) {

include_once $autoload;

return;
}

if (file_exists($autoload = __DIR__ . '/../vendor/autoload.php')) {

include_once $autoload;

return;
}

throw new RuntimeException('Unable to find the Composer autoloader.');
})();
