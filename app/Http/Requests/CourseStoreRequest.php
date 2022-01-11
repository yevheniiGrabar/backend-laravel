<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseStoreRequest extends FormRequest
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
        return [
            'title' => [
                'string',
                'min:3',
                'max:255',
                Rule::unique('courses', 'title')
                    ->where('company_id', $this->company_id)
                    ->where('department_id', $this->department_id)
                    ->whereNot('id', $this->id)
            ],
            'affiliate_ids.*' => [
                Rule::exists('affiliates', 'id'),
            ],
            'moderator_ids.*' => [
                Rule::exists('users', 'id')
            ],
            'department_id' => [
                Rule::exists('departments', 'id'),
            ],
            'logo' => ['image:jpeg,png'],
        ];
    }
}
