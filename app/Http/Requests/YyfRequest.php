<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class YyfRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        if (strpos($validator->getMessageBag()->first(), '|') > 0) {
            list($message, $code) = explode("|", $validator->getMessageBag()->first());
            throw new HttpResponseException(response()->json(['code' => $code, 'errors' => $message]));
        } else {
            $message = $validator->getMessageBag()->first();
            throw new HttpResponseException($message);
        }
    }

/*    protected function user(){
        //$user = session()->get('user');
        //return $user;
        echo 111;
    }*/
}
