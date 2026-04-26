<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListProductsAction
{
    public function execute(): Collection
    {
        return DB::table('products')->get();
    }
}
