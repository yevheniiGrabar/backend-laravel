<?php

namespace App\Enums\CompanyConfig;

use App\Enums\AbstractEnum;

class LanguagesEnum extends AbstractEnum
{
    /** @see CountryEnum + resources/lang/{ru/en/uk} */
    public const UKR = 'Українська';
    public const RUS = 'Русский';
    public const GBR = 'English';
}
