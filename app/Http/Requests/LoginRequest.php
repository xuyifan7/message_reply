<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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

    public function rules(){
        return[
            'name'=>'required|string|min:3',
            'password'=>'required|min:6',
        ];
    }
    public function messages()
    {
        return[
            'name.nullable'=>'用户名不能为空',
            'name.min:3'=>'用户名不能少于三位',
            'password.nullable'=>'密码不能为空',
            'password.min:6'=>'密码不能少于六位',
        ] ;
    }
}
