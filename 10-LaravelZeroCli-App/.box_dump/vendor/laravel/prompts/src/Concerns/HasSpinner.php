<?php

namespace Laravel\Prompts\Concerns;

trait HasSpinner
{





protected array $frames = ['⠂', '⠒', '⠐', '⠰', '⠠', '⠤', '⠄', '⠆'];




protected string $staticFrame = '⠶';




protected int $interval = 75;

public function spinnerFrame(int $count): string
{
return $this->frames[$count % count($this->frames)];
}
}
