<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:90',
            'email' => 'required|email',
            'insurance_type' => 'nullable|string|min:3|max:90',
            'other_insurance_type' => 'nullable|string|min:3|max:90',
            'phone' => 'required|string|min:3|max:120',
          //  'message' => 'nullabel|string|min:3|max:120',
        ];
    }
}
