<?php

namespace App\Http\Requests;

use App\Exceptions\LocalizedValidationException;
use App\Models\LanguageLine;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

abstract class AbstractLocalizedFormRequest extends FormRequest
{

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        $localizedMessages = [];

        $locale = $this->header('Accept-Language', config('app.locale'));


        $validationGroup = LanguageLine::getTranslationsForGroup($locale, 'validation');

        foreach ($this->rules() as $field => $rules) {
            foreach ($rules as $rule) {
                if ($rule instanceof Password) {
                    continue;
                }

                if (is_object($rule)) {
                    $rule = explode(':', $rule->__toString())[0];
                }

                if (!key_exists($rule, $validationGroup)) {
                    continue;
                }
                $localizedMessages["$field.$rule"] = __("validation.$rule", ["field" => $field], $locale);
            }
        }

        return $localizedMessages;
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new LocalizedValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
