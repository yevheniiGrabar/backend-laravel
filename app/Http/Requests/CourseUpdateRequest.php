<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseUpdateRequest extends FormRequest
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
            'department_id' => [Rule::exists('departments', 'id')],
            'affiliate_ids' => [
                'array',
                Rule::exists('affiliates', 'id'),
            ],
            'moderator_ids' => [
                'array',
                Rule::exists('users', 'id')
            ],
            'title' => [
                'string',
                'min:3',
                'max:255',
                Rule::unique('courses', 'title')
                    ->where('company_id', $this->company_id)
                    ->where('department_id', $this->department_id)
                    ->whereNot('id', $this->id)
            ],
            'status' => [Rule::in(Course::SUPPORTED_STATUSES)],
        ];
    }

    public function toArray()
    {
        return array_filter([
            'department_id' => $this->department_id,
            'affiliate_ids' => $this->affiliate_ids,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ]);
    }
}
