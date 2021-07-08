<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileImportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => ['required', 'mimes:xls,xlsx', 'max:10240']
        ];
    }
}
