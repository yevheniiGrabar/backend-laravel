<?php

namespace App\Http\Requests;

use App\Enums\Calendar\CalendarOwnerEnum;
use App\Enums\Calendar\CalendarProviderEnum;
use App\Enums\Calendar\CalendarTimeZoneEnum;
use App\Enums\Calendar\CalscaleEnum;
use Illuminate\Foundation\Http\FormRequest;

class CalendarStoreRequest extends FormRequest
{
    /**
     * @return array
     *
     * @throws \ReflectionException
     */
    public static function getRules()
    {
        return [
            'summary' => 'required|string|min:1|max:255',
            'timezone' => 'string|in:' . CalendarTimeZoneEnum::implode(),
            'scale' => 'string|in:' . CalscaleEnum::implode(),
            'provider' => 'string|in:' . CalendarProviderEnum::implode(),
            'owner_type' => 'string|in:' . CalendarOwnerEnum::implode(),
            'owner_id' => 'required|integer',
            'description' => 'string|min:1|max:2500'
        ];
    }
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
}
