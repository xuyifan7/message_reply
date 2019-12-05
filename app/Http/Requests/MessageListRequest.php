<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageListRequest extends FormRequest
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
            'title'=>'required|max:40|string',
            'content'=>'required|string|max:255',
            'created_at'=>'date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'date_format'=>'该字段必须是日期格式',
        ];
    }
}
