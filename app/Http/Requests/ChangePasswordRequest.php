<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends AbstractLocalizedFormRequest
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
    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password:api', 'different:new_password'],
            'token' => ['nullable', 'string'],
            'new_password' => ['required', Password::min(RegisterRequest::DEFAULT_PASSWORD_SIZE), 'confirmed']
        ];
    }
}
