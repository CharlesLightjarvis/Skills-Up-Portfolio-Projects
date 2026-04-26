<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\Progress;

class ProgressRenderer extends Renderer
{
use Concerns\DrawsBoxes;




protected string $barCharacter = '█';






public function __invoke(Progress $progress): string
{
$filled = str_repeat($this->barCharacter, (int) ceil($progress->percentage() * min($this->minWidth, $progress->terminal()->cols() - 6)));

return match ($progress->state) {
'submit' => $this
->box(
$this->dim($this->truncate($progress->label, $progress->terminal()->cols() - 6)),
$this->dim($filled),
info: $this->fractionCompleted($progress),
),

'error' => $this
->box(
$this->truncate($progress->label, $progress->terminal()->cols() - 6),
$this->dim($filled),
color: 'red',
info: $this->fractionCompleted($progress),
),

'cancel' => $this
->box(
$this->truncate($progress->label, $progress->terminal()->cols() - 6),
$this->dim($filled),
color: 'red',
info: $this->fractionCompleted($progress),
)
->error($progress->cancelMessage),

default => $this
->box(
$this->cyan($this->truncate($progress->label, $progress->terminal()->cols() - 6)),
$this->dim($filled),
info: $this->fractionCompleted($progress),
)
->when(
$progress->hint,
fn () => $this->hint($progress->hint),
fn () => $this->newLine() 
)
};
}




protected function fractionCompleted(Progress $progress): string
{
return number_format($progress->progress).' / '.number_format($progress->total);
}
}
