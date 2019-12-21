<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
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
            'name' => 'required|min:3|unique:user',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'email' => 'required|email|unique:user',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名不能为空|-7',
            'name.min' => '用户名不能少于三位|-8',
            'name.unique' => '用户名已存在|-9',
            'password.required' => '密码不能为空|-10',
            'password.min' => '密码不能少于六位|-11',
            'password_confirmation.required' => '请再次输入密码|-12',
            'password_confirmation.same' => '两次输入密码不一致|-13',
            'email.required' => '邮箱名不能为空|-14',
            'email.unique' => '邮箱名已被注册|-15',
        ];
    }
}
