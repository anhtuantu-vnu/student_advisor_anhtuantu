<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
//    public function failedValidation(Validator $validator): mixed
//    {
//        throw new HttpResponseException(response()->json([
//            'message' => __('Validate errors'),
//            'data' => $validator->errors()
//        ]));
//    }

    /**
     * Define message in validation
     * @return string[]
     */
    public function messages(): array
    {
        return [
            ':attribute.required' => __('validation.required'),
            ':attribute.max' => __('validation.max_content')
        ];
    }
}
