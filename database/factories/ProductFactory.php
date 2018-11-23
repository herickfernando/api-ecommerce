<?php

use App\Domains\Category\Category;
use App\Domains\Product\Product;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker) {
    static $category_id;

    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'price' => $faker->randomFloat(2),
        'category_id' => function () use ($category_id) {
            return $category_id ?? factory(Category::class)->create()->id;
        },
    ];
});
