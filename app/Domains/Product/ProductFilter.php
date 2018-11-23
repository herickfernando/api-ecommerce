<?php

namespace App\Domains\Product;

use App\Domains\Filters;

class ProductFilter extends Filters
{

    /**
     * Get list of columns allowed for search
     * @return array
     */
    protected function getAllowedFilters()
    {
        return [
            'name',
            'description',
        ];
    }
}