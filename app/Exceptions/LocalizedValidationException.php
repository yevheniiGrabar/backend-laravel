<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class LocalizedValidationException extends ValidationException
{
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);

        $this->message = __('validation.default_error', [], request()->header('Accept-Language', config('app.locale')));
    }
}
