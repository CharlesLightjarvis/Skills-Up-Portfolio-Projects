<?php

namespace Laravel\Prompts;

use Illuminate\Support\Collection;

class Grid extends Prompt
{





public array $items;




public int $maxWidth;






public function __construct(array|Collection $items = [], ?int $maxWidth = null)
{
$this->items = $items instanceof Collection ? $items->all() : $items;
$this->maxWidth = $maxWidth ?? static::terminal()->cols() ?: 80;
}




public function display(): void
{
$this->prompt();
}




public function prompt(): bool
{
if ($this->items === []) {
return true;
}

$this->capturePreviousNewLines();

$this->state = 'submit';

static::output()->write($this->renderTheme());

return true;
}




public function value(): bool
{
return true;
}
}
