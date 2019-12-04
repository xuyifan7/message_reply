<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'title'=>'required|max:40|string',
            'content'=>'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'请输入标题',
            'title.max:40'=>'标题长度不能超过40',
            'content.required'=>'请输入留言内容',
        ];
    }
}
