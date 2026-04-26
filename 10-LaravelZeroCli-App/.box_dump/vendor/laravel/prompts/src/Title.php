<?php

namespace Laravel\Prompts;

class Title extends Prompt
{
public function __construct(public string $title)
{

}




public function prompt(): bool
{
$this->writeDirectly($this->renderTheme());

return true;
}




public function display(): void
{
$this->prompt();
}




public function value(): bool
{
return true;
}
}
