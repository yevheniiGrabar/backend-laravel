<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        /** @var Company|null $company */
        $company = $this->user()?->companies()->first();

        return [
            'title' => [
                'string',
                'min:3',
                'max:255',
                Rule::unique('departments')
                    ->where('title', $this->title)
                    ->where('company_id', $company->id ?? null)
                    ->whereNot('id', $this->id)
            ],
            'company_id' => [
                Rule::exists('companies', 'id'),
            ],
            'affiliate_ids' => [
                'array',
                Rule::exists('affiliates', 'id'),
            ],
            'course_ids' =>  [
                'array',
                Rule::exists('courses', 'id'),
            ],
        ];
    }
}
