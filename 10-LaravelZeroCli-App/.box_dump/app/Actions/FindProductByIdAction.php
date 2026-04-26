<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class FindProductByIdAction
{
public function execute(int $id): ?object
{
return DB::table('products')
->where('id', $id)
->first();
}
}
