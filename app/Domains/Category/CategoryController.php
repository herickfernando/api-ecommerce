<?php

namespace App\Domains\Category;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function getAll()
    {
        return Category::all();
    }
}