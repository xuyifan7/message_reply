<?php

namespace App\Http\Requests;

class LoginRequest extends YyfRequest
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
            'name.required'=>'用户名不能为空|-22',
            'name.min'=>'用户名不能少于三位|-23',
            'password.required'=>'密码不能为空|-24',
            'password.min'=>'密码不能少于六位|-25',
        ] ;
    }

/*    protected function failedValidation(Validator $validator)
    {
        if(strpos($validator->getMessageBag()->first(),'|') > 0){
            list($message, $code) = explode("|", $validator->getMessageBag()->first());
            throw new HttpResponseException($this->fail($message,$code));
        }else {
            $message = $validator->getMessageBag()->first();
            throw new HttpResponseException($message);
        }
    }

    protected function fail($code,  $errors) : JsonResponse{
        return response()->json(
            [
                'code' => $code,
                'errors' => $errors
            ]
        );
    }*/
}
