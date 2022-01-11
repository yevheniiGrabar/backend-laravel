<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AffiliateUpdateRequest extends FormRequest
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
    public function rules()
    {
        return self::getRules();
    }

    /**
     * @return array
     */
    public static function getRules(): array
    {
        /** @var Company|null $company */
        $company = Auth::user()?->companies()->first();

        return [
            'title' => [
                'string',
                'min:1',
                'max:255',
                Rule::unique('affiliates')
                    ->where('title', request('title'))
                    ->where('company_id', $company->id ?? null)
            ],
            'company_id' => [
                Rule::exists('companies', 'id'),
            ],
            'courses_ids' => [
                'array',
                Rule::exists('courses', 'id'),
            ]
        ];
    }
}
