<?php

namespace App\Domains\Category;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function all()
    {
        return Category::all();
    }
}