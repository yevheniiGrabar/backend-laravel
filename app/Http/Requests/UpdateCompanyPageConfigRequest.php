<?php

namespace App\Http\Requests;

use App\Enums\CompanyConfig\CountryEnum;
use App\Enums\CompanyConfig\LocaleEnum;
use App\Enums\CompanyConfig\Pages\OverallPageEnum;
use App\Enums\CompanyConfig\Pages\PagesListEnum;
use App\Models\Company;
use App\Rules\ValidateKeys;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyPageConfigRequest extends FormRequest
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
            'sidebar_icon' => 'nullable|image',
            'entity_id' => 'required|integer',
            'page_config' => 'nullable|array',
            'page_config.*' => ['required_with:page_config', 'string', new ValidateKeys(OverallPageEnum::class)],
            'direction_type' => [
                'in:' . PagesListEnum::implode(),
                'string',
                Rule::unique('company_pages')
                    ->where('entity_id', request('company_id'))
                    ->where('entity_type', Company::class)
                    ->where('direction_type', request('direction_type'))
            ],
            'sidebar_name' => [
                'string',
                'min:2',
                'max:255',
                Rule::unique('company_pages')
                    ->where('entity_id', request('company_id'))
                    ->where('entity_type', Company::class)
                    ->where('sidebar_name', request('sidebar_name'))
            ],
        ];
    }
}
