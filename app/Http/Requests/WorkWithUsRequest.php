<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsRequest extends FormRequest
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
            'area' => 'required|string|min:3|max:90',
            'file' => 'required|max:10000|mimes:pdf,docx',
         //   'portfolio' => 'nullabel|string|min:3|max:120',
          //  'message' => 'nullabel|string|min:3|max:120',
        ];
    }
}
