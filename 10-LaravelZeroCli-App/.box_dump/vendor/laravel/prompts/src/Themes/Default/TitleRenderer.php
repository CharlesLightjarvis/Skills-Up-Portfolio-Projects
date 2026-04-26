<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\Title;

class TitleRenderer extends Renderer
{



public function __invoke(Title $title): string
{
return "\033]0;{$title->title}\007";
}
}
