<?php

namespace PHPacker\PHPacker\Contracts;

interface RemoteRepositoryService
{

public function releaseData(): ?array;

public function downloadReleaseAssets(string $destination): string;

public function latestVersion(): ?string;
}
