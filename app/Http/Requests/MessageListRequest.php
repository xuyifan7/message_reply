<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageListRequest extends YyfRequest
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
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'integer' => '请输入正确的页数!|-1',
            'min' => ':attribute 这个参数不能小于:min|-2'
        ];
    }
}
