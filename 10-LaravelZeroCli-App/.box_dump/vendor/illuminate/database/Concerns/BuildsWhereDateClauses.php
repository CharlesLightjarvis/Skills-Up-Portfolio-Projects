<?php

namespace Illuminate\Database\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Arr;

trait BuildsWhereDateClauses
{






public function wherePast($columns)
{
return $this->wherePastOrFuture($columns, '<', 'and');
}







public function whereNowOrPast($columns)
{
return $this->wherePastOrFuture($columns, '<=', 'and');
}







public function orWherePast($columns)
{
return $this->wherePastOrFuture($columns, '<', 'or');
}







public function orWhereNowOrPast($columns)
{
return $this->wherePastOrFuture($columns, '<=', 'or');
}







public function whereFuture($columns)
{
return $this->wherePastOrFuture($columns, '>', 'and');
}







public function whereNowOrFuture($columns)
{
return $this->wherePastOrFuture($columns, '>=', 'and');
}







public function orWhereFuture($columns)
{
return $this->wherePastOrFuture($columns, '>', 'or');
}







public function orWhereNowOrFuture($columns)
{
return $this->wherePastOrFuture($columns, '>=', 'or');
}









protected function wherePastOrFuture($columns, $operator, $boolean)
{
$type = 'Basic';
$value = Carbon::now();

foreach (Arr::wrap($columns) as $column) {
$this->wheres[] = compact('type', 'column', 'boolean', 'operator', 'value');

$this->addBinding($value);
}

return $this;
}








public function whereToday($columns, $boolean = 'and')
{
return $this->whereTodayBeforeOrAfter($columns, '=', $boolean);
}







public function whereBeforeToday($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '<', 'and');
}







public function whereTodayOrBefore($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '<=', 'and');
}







public function whereAfterToday($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '>', 'and');
}







public function whereTodayOrAfter($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '>=', 'and');
}







public function orWhereToday($columns)
{
return $this->whereToday($columns, 'or');
}







public function orWhereBeforeToday($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '<', 'or');
}







public function orWhereTodayOrBefore($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '<=', 'or');
}







public function orWhereAfterToday($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '>', 'or');
}







public function orWhereTodayOrAfter($columns)
{
return $this->whereTodayBeforeOrAfter($columns, '>=', 'or');
}









protected function whereTodayBeforeOrAfter($columns, $operator, $boolean)
{
$value = Carbon::today()->format('Y-m-d');

foreach (Arr::wrap($columns) as $column) {
$this->addDateBasedWhere('Date', $column, $operator, $value, $boolean);
}

return $this;
}
}
