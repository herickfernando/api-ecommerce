<?php


use App\Domains\Category\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $user = new Category();
        $user->name = 'Accessories';
        $user->save();
    }
}