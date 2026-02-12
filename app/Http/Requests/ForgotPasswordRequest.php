<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Contracts\Service\Attribute\Required;

class ForgotPasswordRequest extends FormRequest
{
    use ApiResponse;

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
            'email' => 'required|email|exists:users,email',
        ];
    }


    public function messages()
    {
        return [
            'email.exists' => 'Verifique seus dados e tente novamente.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->notAcceptable(
            'Verifique seus dados e tente novamente.',
            'E-mail não encontrado'
        ));
    }
}
