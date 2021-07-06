<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotMessageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'briefly' => ['required', 'string', 'max:80', 'unique:bot_messages,briefly'],
            'message' => ['required', 'string', 'max:255']
        ];
    }
}
