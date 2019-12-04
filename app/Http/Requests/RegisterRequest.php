<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'=>'required|min:3|unique:user',
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required|same:password',
            'email'=>'required|email|unique:user',
        ];
    }

    public function messages()
    {
        return[
            'name.nullable'=>'用户名不能为空',
            'name.min:3'=>'用户名不能少于三位',
            'name.unique:user'=>'用户名已存在',
            'password.nullable'=>'密码不能为空',
            'password.min:6'=>'密码不能少于六位',
            'password_confirmation.nullable'=>'请再次输入密码',
            'password_confirmation.same:password'=>'两次输入密码不一致',
            'email.nullable'=>'邮箱名不能为空',
            'email.unique:user'=>'邮箱名已被注册',
        ] ;
    }
}
