<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyUpdateRequest extends FormRequest
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
            'content'=>'required|string|max:255',
            'reply_id'=>'required|max:20|exists:message,id'
        ];
    }

    public function messages()
    {
        return [
            /*'content.required' => '请输入回复内容',
            'content.max' => '回复内容过长',
            'reply_id.required'=>'请输入回复的留言id',
            'reply_id.exists'=>'回复的留言不存在',*/
        ];
    }
}
