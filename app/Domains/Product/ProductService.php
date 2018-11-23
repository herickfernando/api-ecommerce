<?php

namespace App\Domains\Product;

use App\Domains\CRUDService;

class ProductService extends CRUDService
{
    public $modelClass = Product::class;

    public function columnsFilter(): array
    {
        return [];
    }

    /**
     * @param Product $model
     * @param array $data
     */
    protected function fill(&$model, array $data)
    {
        $model->name = $data['name'];
        $model->description = $data['description'];
        $model->price = $data['price'];
        $model->category_id = $data['category_id'];
    }
}