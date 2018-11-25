<?php

namespace App\Domains\Product;

use App\Domains\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'The selected category is invalid.'
        ];
    }
}
