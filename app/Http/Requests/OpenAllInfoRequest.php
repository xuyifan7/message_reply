<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenAllInfoRequest extends BaseRequest
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
            'rid'=>'required|exists:reply',
        ];
    }

    public function messages()
    {
        return [
            'id.required'=>'缺少留言ID|-20',
            'id.exists'=>'留言不存在|-21',
            'rid.required'=>'缺少留言的回复ID|-20',
            'rid.exists'=>'留言的回复不存在|-21',
        ];
    }
}
