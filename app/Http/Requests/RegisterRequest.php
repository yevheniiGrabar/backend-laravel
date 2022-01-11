<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends AbstractLocalizedFormRequest
{
    public const DEFAULT_PASSWORD_SIZE = 8;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'first_name' => ['string', 'min:1', 'max:50'],
            'last_name' => ['string', 'min:1', 'max:50'],
            'phone' => ['string', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:10', 'max:15', Rule::unique('users', 'phone')],
            'email' => ['email', 'required', Rule::unique('users', 'email')],
            'password' => ['required', Password::min(self::DEFAULT_PASSWORD_SIZE), 'confirmed'],
        ];
    }


    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'password' => $this->password,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
        ]);
    }
}
