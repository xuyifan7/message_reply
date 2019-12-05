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
            //
            'title'=>'required|max:40|string',
            'content'=>'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'title.required'=>'请输入标题',
            'title.max'=>'标题长度过长',
            'content.required'=>'请输入留言内容',
            'content.max'=>'留言内容过长',
        ];
    }
}
