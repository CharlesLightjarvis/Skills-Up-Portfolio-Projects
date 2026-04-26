<?php declare(strict_types=1);








namespace PHPUnit\Runner\Phpt;

use function assert;
use function defined;
use function dirname;
use function file_put_contents;
use function str_replace;
use function var_export;
use PHPUnit\TextUI\Configuration\Registry as ConfigurationRegistry;
use SebastianBergmann\Template\InvalidArgumentException;
use SebastianBergmann\Template\Template;

/**
@no-named-arguments




*/
final readonly class Renderer
{






public function render(string $phptFile, string $code): string
{
return str_replace(
[
'__DIR__',
'__FILE__',
],
[
"'" . dirname($phptFile) . "'",
"'" . $phptFile . "'",
],
$code,
);
}

/**
@param-out





*/
public function renderForCoverage(string &$job, bool $pathCoverage, ?string $codeCoverageCacheDirectory, array $files): void
{
$template = new Template(
__DIR__ . '/templates/phpt.tpl',
);

$composerAutoload = '\'\'';

if (defined('PHPUNIT_COMPOSER_INSTALL')) {
$composerAutoload = var_export(PHPUNIT_COMPOSER_INSTALL, true);
}

$phar = '\'\'';

if (defined('__PHPUNIT_PHAR__')) {
$phar = var_export(__PHPUNIT_PHAR__, true);
}

if ($codeCoverageCacheDirectory === null) {
$codeCoverageCacheDirectory = 'null';
} else {
$codeCoverageCacheDirectory = "'" . $codeCoverageCacheDirectory . "'";
}

$bootstrap = '';

if (ConfigurationRegistry::get()->hasBootstrap()) {
$bootstrap = ConfigurationRegistry::get()->bootstrap();
}

$template->setVar(
[
'bootstrap' => $bootstrap,
'composerAutoload' => $composerAutoload,
'phar' => $phar,
'job' => $files['job'],
'coverageFile' => $files['coverage'],
'driverMethod' => $pathCoverage ? 'forLineAndPathCoverage' : 'forLineCoverage',
'codeCoverageCacheDirectory' => $codeCoverageCacheDirectory,
],
);

file_put_contents($files['job'], $job);

$rendered = $template->render();

assert($rendered !== '');

$job = $rendered;
}
}
