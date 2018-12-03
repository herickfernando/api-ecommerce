<?php

namespace App\Domains\Product;

use App\Domains\FormRequest;
use App\Http\Validations\Base64Image;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'exists:categories,id',
            'description' => 'required',
            'images' => [
                'array',
                new Base64Image(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'category_id.exists' => 'The selected category is invalid.'
        ];
    }
}
