<?php

namespace Tests\Feature\Controller;

use App\Domains\Category\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    const ENDPOINT = 'api/backoffice/categories/all';

    public function testMustListAllCategories()
    {
        factory(Category::class, 5)->create();
        $this
            ->json('GET', self::ENDPOINT)
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
