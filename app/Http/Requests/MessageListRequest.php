<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageListRequest extends BaseRequest
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
            'required' => ':attribute 是必填项|-3',
            'integer' => ':attribute 这个参数应为整数|-1',
            'min' => ':attribute 这个参数不能小于:min|-2'
        ];
    }
}
