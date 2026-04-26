<?php declare(strict_types=1);








namespace PHPUnit\TextUI\XmlConfiguration\Logging;

use PHPUnit\TextUI\Configuration\File;

/**
@no-named-arguments
@immutable



*/
final readonly class Otr
{
private File $target;
private bool $includeGitInformation;

public function __construct(File $target, bool $includeGitInformation)
{
$this->target = $target;
$this->includeGitInformation = $includeGitInformation;
}

public function target(): File
{
return $this->target;
}

public function includeGitInformation(): bool
{
return $this->includeGitInformation;
}
}
