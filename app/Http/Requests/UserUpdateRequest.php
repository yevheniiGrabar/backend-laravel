<?php

namespace App\Http\Requests;

use App\Enums\Users\MediaLinksEnum;
use App\Enums\Users\RolesEnum;
use App\Enums\Users\UserStatuses;
use App\Rules\ValidateKeys;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
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
     * @throws \ReflectionException
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'min:1', 'max:50'],
            'last_name' => ['string', 'min:1', 'max:50'],
            'phone' => ['string', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:10', 'max:15', 'unique:users,phone'],
            'email' => ['email', 'unique:users,email'],
            'position' => ['string', 'max:150'],
            'password' => ['string', 'min:8', 'confirmed'],
            'country' => ['string', 'max:150'],
            'city' => ['string', 'max:150'],
            'status' => ['string', 'in:' . UserStatuses::implode()],
            'roles' => ['array', 'in:' . RolesEnum::implode()],
            'medialinks' => ['array', 'nullable'],
            'medialinks.*' => [new ValidateKeys(MediaLinksEnum::class)],
        ];
    }

    // ????
    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'position' => $this->position,
            'country' => $this->country,
            'city' => $this->city,
            'avatar' => $this->avatar,
        ]);
    }
}
