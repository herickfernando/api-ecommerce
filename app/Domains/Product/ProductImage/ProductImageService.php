<?php

namespace App\Domains\Product\ProductImage;

use App\Domains\Image\Image;
use App\Domains\Product\Product;
use Facades\App\Domains\Image\ImageService;
use Illuminate\Database\Query\Builder;

class ProductImageService
{

    public function create(Product $product, array $images)
    {
        /** @var Builder $productImages */
        $productImages = $product->images();

        $imagesIds = array_pluck($images, 'id');
        $imagesIds = array_filter($imagesIds, function ($id) {
            return $id !== null;
        });

        if (!empty($imagesIds)) {
            $productImages
                ->whereNotIn('image_id', $imagesIds)
                ->delete();
        }

        foreach ($images as $image) {
            if (isset($image['id'])) {
                continue;
            }
            $productImage = new ProductImage();
            $productImage->product_id = $product->id;

            $specificPath = sprintf('product/%s/', $product->id);

            /** @var Image $image */
            $image = ImageService::createImage($image['image_url'], $specificPath);

            $productImage->image_id = $image->id;
            $productImage->save();
        }
    }
}
