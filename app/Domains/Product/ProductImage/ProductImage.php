<?php

namespace App\Domains\Product\ProductImage;

use App\Domains\Image\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductImage
 * @package App\Domains\Product\ProductImage
 * @property string $product_id
 * @property string $image_id
 * @property Image  $image
 */
class ProductImage extends Model
{
    use SoftDeletes;

    protected $table = 'product_images';
    public $incrementing = false;
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}