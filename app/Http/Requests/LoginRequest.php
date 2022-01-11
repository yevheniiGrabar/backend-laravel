<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends AbstractLocalizedFormRequest
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
            "grant_type" => ['required', Rule::in(config('auth.allowed_grant_types'))],
            "client_id" => ['required', Rule::exists('oauth_clients', 'id')],
            "client_secret" => ['required', Rule::exists('oauth_clients', 'secret')],
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', Password::min(RegisterRequest::DEFAULT_PASSWORD_SIZE)],
        ];
    }

    public function toArray()
    {
        return [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => $this->grant_type,
            'username' => $this->email,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public function getCredentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
