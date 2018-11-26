<?php

namespace App\Domains\Product;

use App\Domains\CRUDService;
use App\Domains\Product\ProductImage\ProductImageService;

class ProductService extends CRUDService
{
    public $modelClass = Product::class;

    public function create($data)
    {
        /** @var Product $product */
        $product = parent::create($data);

        if (isset($data['images'])) {
            $productImageService = new ProductImageService();
            $productImageService->create($product, $data['images']);
        }

        return $product;
    }

    /**
     * @param Product $model
     * @param array   $data
     */
    protected function fill(&$model, array $data)
    {
        $model->name = $data['name'];
        $model->description = $data['description'];
        $model->price = $data['price'];
        $model->category_id = $data['category_id'];
    }

    public function update($model, $data)
    {
        /** @var Product $product */
        $product = parent::update($model, $data);

        if (isset($data['images'])) {
            $productImageService = new ProductImageService();
            $productImageService->create($product, $data['images']);
        }

        return $product;
    }
}
