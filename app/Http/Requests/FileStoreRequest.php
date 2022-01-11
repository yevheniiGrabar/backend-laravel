<?php

namespace App\Http\Requests;

use App\Enums\Files\FilesEnum;
use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|max:10240',
            'format' => 'string|in:' . FilesEnum::implode()
        ];
    }
}
