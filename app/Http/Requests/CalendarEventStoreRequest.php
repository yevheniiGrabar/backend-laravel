<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarEventStoreRequest extends FormRequest
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
        return [
            'summary' => 'required|string|min:1|max:255',
            'calendar_id' => 'required|string|exists:calendars,id',
            'color' => 'nullable|string',
            'description' => 'nullable|string|max:2500',
            'dt_start' => 'required|date',
            'dt_end' => 'nullable|date',
            'subscribers' => 'array',
            'subscribers.*' => 'required_with:subscribers|string|exists:users,email'
        ];
    }
}
