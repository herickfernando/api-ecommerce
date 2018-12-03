<?php

namespace App\Domains\CSV;

use App\Domains\FormRequest;

class CSVRequest extends FormRequest
{
    public function rules()
    {
        return [
            'csv' => 'required',
        ];
    }
}
