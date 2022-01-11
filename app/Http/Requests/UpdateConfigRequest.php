<?php

namespace App\Http\Requests;

use App\Enums\CompanyConfig\CountryEnum;
use App\Enums\CompanyConfig\LocaleEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigRequest extends FormRequest
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
     * @throws \ReflectionException
     *
     * @return array
     */
    public function rules()
    {
        return self::getRules();
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    public static function getRules(): array
    {
        return [
            'title' => 'unique:companies,title|string|min:2|max:255',
            'country' => 'string|in:' . CountryEnum::implode(),
            'locale' => 'string|in:' . LocaleEnum::implode(),
            'color' => ['regex:%^#([a-f0-9]{6}|[a-f0-9]{3})$%i', 'nullable'],
            'is_multi_country' => 'boolean',
            'buttons_config' => 'nullable|array',
            'buttons_config.radius' => 'required_with:buttons_config|string',
            'buttons_config.width' => 'required_with:buttons_config|string'
        ];
    }
}
