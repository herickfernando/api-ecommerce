<?php

namespace App\Domains\Product\ProductImage;

use App\Domains\Image\Image;
use App\Domains\Product\Product;
use Facades\App\Domains\Image\ImageService;

class ProductImageService
{

    public function create(Product $product, array $images)
    {
        foreach ($images as $image) {
            $productImage = new ProductImage();
            $productImage->product_id = $product->id;

            $specificPath = sprintf('product/%s/', $product->id);

            /** @var Image $image */
            $image = ImageService::createImage($image, $specificPath);

            $productImage->image_id = $image->id;
            $productImage->save();
        }
    }
}