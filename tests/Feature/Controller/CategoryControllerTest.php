<?php

namespace Tests\Feature\Controller;

use App\Domains\Category\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    public function testMustListAllCategories()
    {
        factory(Category::class, 5)->create();
        $this
            ->json('GET', 'api/backoffice/categories')
            ->assertSuccessful()
            ->assertJsonStructure([
                0 => [
                    'id',
                    'name',
                ],
            ])
            ->assertJsonCount(5);
    }
}
