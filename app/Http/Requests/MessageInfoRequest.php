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
            'id'=>'required|exists:message',
        ];
    }

    public function messages()
    {
        return [
            'id.required'=>'缺少留言ID|-20',
            'id.exists'=>'留言不存在|-21',
        ];
    }

}
