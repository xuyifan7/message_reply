<?php

namespace App\Http\Requests;

class ReplyUpdateRequest extends BaseRequest
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
            'reply_content'=>'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'reply_content.required' => '请输入回复内容|-3',
            'reply_content.max' => '回复内容过长|-4',
        ];
    }

}
