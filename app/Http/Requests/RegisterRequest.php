<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'tax_id'=> 'required|numeric|digits:11',
            'password'=> 'required|confirmed|string',
            'password_confirmation'=> 'required|string',
            'company' => 'required|string|min:3|max:90',
            'segment'=> 'required|string|min:3|max:90',
        ];
    }
}
