<?php

namespace Tests\Feature\Controller;

use App\Domains\Category\Category;
use App\Domains\Product\Product;
use App\Domains\Product\ProductImage\ProductImage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    const ENDPOINT = 'api/backoffice/products';

    public function testMustRegisterProduct()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $category->id,
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(201)
            ->assertJsonStructure(['id']);
    }

    public function testMustNotRegisterAnUnnamedProduct()
    {
        $this->withExceptionHandling();
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $payload = [
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $category->id,
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => [
                        0 => 'The name field is required.',
                    ],
                ],
            ]);
    }

    public function testMustNotRegisterAProductWithAStringPrice()
    {
        $this->withExceptionHandling();
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 'test',
            'category_id' => $category->id,
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'price' => [
                        0 => 'The price must be a number.',
                    ],
                ],
            ]);
    }

    public function testMustNotRegisterAProductWithACategoryNotFound()
    {
        $this->withExceptionHandling();
        /** @var Category $category */
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => 'asd',
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'category_id' => [
                        0 => 'The selected category is invalid.',
                    ],
                ],
            ]);
    }

    public function testMustListAllPagedProducts()
    {
        factory(Product::class, 30)->create();

        $uri = sprintf('%s?per_page=10&search=', self::ENDPOINT);
        $response = $this
            ->json('GET', $uri)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'name',
                        'description',
                        'price',
                        'category_id',
                        'category_name',
                    ],
                ],
            ]);

        $decodeResponseJson = $response->decodeResponseJson();
        self::assertEquals(1, $decodeResponseJson['current_page']);
        self::assertEquals(3, $decodeResponseJson['last_page']);
        self::assertEquals(10, $decodeResponseJson['per_page']);
        self::assertEquals(30, $decodeResponseJson['total']);
        self::assertCount(10, $decodeResponseJson['data']);
    }

    public function testMustListAllPagedProductsFilteredByATerm()
    {
        factory(Product::class, 29)->create(['name' => 'Must not return this categories']);

        /** @var Product $product */
        $product = factory(Product::class)->create(['name' => 'Must return this category']);

        $uri = sprintf('%s?per_page=10&search=Must return', self::ENDPOINT);
        $response = $this
            ->json('GET', $uri)
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    0 => [
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'category_id' => $product->category_id,
                        'category_name' => $product->category_name,
                    ],
                ],
            ]);

        $decodeResponseJson = $response->decodeResponseJson();
        self::assertEquals(1, $decodeResponseJson['current_page']);
        self::assertEquals(1, $decodeResponseJson['last_page']);
        self::assertEquals(10, $decodeResponseJson['per_page']);
        self::assertEquals(1, $decodeResponseJson['total']);
        self::assertCount(1, $decodeResponseJson['data']);
    }

    public function testYouMustReturnInformationForAProductById()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create(['name' => 'Must return this category']);

        $uri = sprintf('%s/%s', self::ENDPOINT, $product->id);
        $this
            ->json('GET', $uri)
            ->assertSuccessful()
            ->assertJson([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'category_name' => $product->category_name,
            ]);
    }

    public function testYouMustChangeTheInformationForAProduct()
    {
        $this->withExceptionHandling();
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $product->category_id,
        ];

        $uri = sprintf('%s/%s', self::ENDPOINT, $product->id);
        $this
            ->json('PUT', $uri, $payload)
            ->assertSuccessful()
            ->assertJson(['id' => $product->id]);
    }

    public function testYouMustRemoveAProduct()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $uri = sprintf('%s/%s', self::ENDPOINT, $product->id);
        $this
            ->json('DELETE', $uri)
            ->assertStatus(204);

        $productInDatabase = Product::find($product->id);
        self::assertNull($productInDatabase);
    }

    public function testMustRegisterProductWithImages()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $imageBase64Json = file_get_contents(base_path('tests/Mock/base64Image.json'));
        $imageBase64 = json_decode($imageBase64Json);
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $category->id,
            'images' => [
                [
                    'image_url' => $imageBase64->base64,
                ],
            ],
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(201)
            ->assertJsonStructure(['id']);
    }

    public function testMustRegisterProductWithTextFileInTheImages()
    {
        $this->withExceptionHandling();
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $textBase64Json = file_get_contents(base_path('tests/Mock/base64Text.json'));
        $textBase64 = json_decode($textBase64Json);
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $category->id,
            'images' => [
                [
                    'image_url' => $textBase64->base64,
                ],
            ],
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'images' => [
                        'Sent files are not images.',
                    ],
                ],
            ]);
    }

    public function testMustRegisterProductWithStringImages()
    {
        $this->withExceptionHandling();
        /** @var Category $category */
        $category = factory(Category::class)->create(['name' => 'Category Test']);
        $imageBase64Json = file_get_contents(base_path('tests/Mock/base64Image.json'));
        $imageBase64 = json_decode($imageBase64Json);
        $payload = [
            'name' => 'Product Test',
            'description' => 'Product of Test',
            'price' => 150.00,
            'category_id' => $category->id,
            'images' => $imageBase64->base64,
        ];
        $this
            ->json('POST', self::ENDPOINT, $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'images' => [
                        'The images must be an array.',
                    ],
                ],
            ]);
    }

    protected function tearDown()
    {
        Product
            ::withTrashed()
            ->get()
            ->each(function (Product $product) {
                $directory = sprintf('public/upload/product/%s', $product->id);
                Storage::deleteDirectory($directory);
            });
        parent::tearDown();
    }
}
