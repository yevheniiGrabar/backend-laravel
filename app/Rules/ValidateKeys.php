<?php

namespace App\Rules;

use App\Enums\AbstractEnum;
use Illuminate\Contracts\Validation\Rule;

class ValidateKeys implements Rule
{
    /**
     * ValidateKeys constructor.
     *
     * @param string|AbstractEnum $enum
     */
    public function __construct(private string $enum)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @throws \ReflectionException
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $slice = substr(strrchr($attribute, '.'), 1);

        return in_array($slice, $this->enum::getAllValues());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute isn\'t valid key.';
    }
}
