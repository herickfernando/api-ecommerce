<?php

namespace App\Domains;

use Illuminate\Foundation\Http\FormRequest as FormRequestBase;
use Illuminate\Support\Facades\Lang;

class FormRequest extends FormRequestBase
{
    public function messages()
    {
        return Lang::get('validation');
    }
}
