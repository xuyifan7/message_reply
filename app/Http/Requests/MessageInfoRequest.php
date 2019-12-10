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
