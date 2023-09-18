<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Traits\ResponseTrait;

class PlanValidation extends BaseRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        parent::__construct();
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

}
