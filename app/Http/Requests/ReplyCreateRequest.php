<?php

namespace App\Http\Requests;

class ReplyCreateRequest extends BaseRequest
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
            'message_id' => 'required|exists:message,id',
            'reply_content' => 'required|string|max:255',
            'parent_id' => 'sometimes|min:1|exists:reply,rid',
        ];
    }

    public function messages()
    {
        return [
            'message_id.required' => '请输入回复的留言ID|-3',
            'message_id.exists' => '回复的留言ID不存在|-3',
            'reply_content.required' => '请输入回复内容|-3',
            'reply_content.max' => '回复内容过长|-4',
            'parent_id.min' => '回复的回复ID不正确|-5',
            'parent_id.exists' => '回复的回复不存在|-6',
        ];
    }

}
