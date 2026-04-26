<?php declare(strict_types=1);








namespace PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report;

use PHPUnit\TextUI\Configuration\Directory;

/**
@no-named-arguments
@immutable



*/
final readonly class Xml
{
private Directory $target;
private bool $includeSource;

public function __construct(Directory $target, bool $includeSource)
{
$this->target = $target;
$this->includeSource = $includeSource;
}

public function target(): Directory
{
return $this->target;
}

public function includeSource(): bool
{
return $this->includeSource;
}
}
