<?php

namespace App\Domains\Product;

use App\Domains\Category\Category;
use App\Domains\DomainModel;
use App\Domains\Filters;

/**
 * Class Product
 * @package App\Domains\Product
 * @property string $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property string $category_id
 * @property string $category_name
 * @property Category $category
 */
class Product extends DomainModel
{
    protected $appends = [
        'category_name'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryNameAttribute()
    {
        return $this
            ->category()
            ->first()
            ->name;
    }

    public function scopeFilter($query, Filters $filters)
    {
        $filters->apply($query);
    }
}