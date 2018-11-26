<?php

namespace App\Domains\Product;

use App\Domains\Category\Category;
use App\Domains\DomainModel;
use App\Domains\Filters;
use App\Domains\Product\ProductImage\ProductImage;

/**
 * Class Product
 * @package App\Domains\Product
 * @property string   $id
 * @property string   $name
 * @property string   $description
 * @property double   $price
 * @property string   $category_id
 * @property string   $category_name
 * @property Category $category
 * @property ProductImage[] $images
 */
class Product extends DomainModel
{
    protected $appends = ['category_name'];
    protected $casts = [
        'price' => 'double',
    ];

    public function getCategoryNameAttribute()
    {
        return $this
            ->category()
            ->first()
            ->name;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, Filters $filters)
    {
        $filters->apply($query);
    }

    public function images()
    {
        return $this
            ->hasMany(ProductImage::class)
            ->with('image');
    }
}
