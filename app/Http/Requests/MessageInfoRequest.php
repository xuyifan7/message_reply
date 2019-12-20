<?php

namespace App\Http\Requests;

class MessageInfoRequest extends YyfRequest
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
            'id' => 'required|exists:message',
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute 是必填项|-20',
            'id.exists' => '留言不存在|-21',
            'integer' => ':attribute 这个参数应为整数|-1',
            'min' => ':attribute 这个参数不能小于:min|-2'
        ];
    }

}
