<?php

namespace App\Http\Requests;

use App\Enums\Users\RolesEnum;
use App\Enums\Users\UserStatuses;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /** @var mixed */
    protected $first_name;
    /** @var mixed */
    protected $last_name;
    /** @var mixed*/
    protected $email;
    protected $phone;
    /** @var mixed */
    protected $position;
    /** @var mixed */
    protected $country;
    /** @var mixed */
    protected $city;
    /** @var mixed */
    protected $affiliate_id;

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
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'min:1', 'max:50'],
            'last_name' => ['string', 'min:1', 'max:50'],
            'phone' => ['string', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:10', 'max:15'],
            'email' => ['email', 'unique:users,email'],
            'country' => ['string', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'city' => ['string', 'max:150'],
            'status' => ['string', 'in:' . UserStatuses::implode()],
            'affiliate_id' => ['required', 'integer', 'exists:affiliates,id'],
            'roles' => ['required', 'array', 'in:' . RolesEnum::implode()]
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'country' => $this->country,
            'city' => $this->city,
            'affiliate_id' => $this->affiliate_id
        ]);
    }
}
