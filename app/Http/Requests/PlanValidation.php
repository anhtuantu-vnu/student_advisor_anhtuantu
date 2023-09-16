<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlanValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => "required|max:255",
            'description' => 'required',
            'create_by' => "required"
        ];
    }


//    /**
//     * Define message in validation
//     * @return string[]
//     */
//    public function messages(): array
//    {
//        return [
//            'name.required' => 'Name is required!',
//            'description.required' => 'Description is required!',
//            'created_by.required' => 'Password is too short',
//        ];
//    }
}
