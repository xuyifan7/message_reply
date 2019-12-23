<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    protected $user;

    public function __construct()
    {
        $this->user = session('user');
    }

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
        } else {
            $message = $validator->getMessageBag()->first();
            $code = 500;
        }
        throw new HttpResponseException(response()->json(['code' => $code, 'errors' => $message]));
    }

    public function getUser()
    {
        return $this->user;
    }
}
