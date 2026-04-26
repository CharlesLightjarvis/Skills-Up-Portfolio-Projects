<?php

namespace Illuminate\Database\Eloquent;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Events\ModelsPruned;
use LogicException;
use Throwable;

trait Prunable
{






public function pruneAll(int $chunkSize = 1000)
{
$total = 0;

$this->prunable()
->when(static::isSoftDeletable(), function ($query) {
$query->withTrashed();
})->chunkById($chunkSize, function ($models) use (&$total) {
$models->each(function ($model) use (&$total) {
try {
$model->prune();

$total++;
} catch (Throwable $e) {
$handler = app(ExceptionHandler::class);

if ($handler) {
$handler->report($e);
} else {
throw $e;
}
}
});

event(new ModelsPruned(static::class, $total));
});

return $total;
}






public function prunable()
{
throw new LogicException('Please implement the prunable method on your model.');
}






public function prune()
{
$this->pruning();

return static::isSoftDeletable()
? $this->forceDelete()
: $this->delete();
}






protected function pruning()
{

}
}
