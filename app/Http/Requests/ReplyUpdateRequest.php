<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ReplyUpdateRequest extends FormRequest
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
            'content'=>'required|string|max:255',
            'reply_id'=>'required|max:20|exists:message,id'
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '请输入回复内容|-3',
            'content.max' => '回复内容过长|-4',
            'reply_id.required'=>'请输入回复的留言id|-5',
            'reply_id.exists'=>'回复的留言不存在|-6',
        ];
    }

    protected function failedValidation(Validator $validator)
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
    }
}
