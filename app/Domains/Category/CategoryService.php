<?php

namespace App\Domains\Category;

use App\CRUDService;

class CategoryService extends CRUDService
{
    public $modelClass = Category::class;

    public function columnsFilter(): array
    {
        return ['name'];
    }
}