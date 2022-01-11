<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TranslationLoader\LanguageLine as BaseLanguageLineModel;

class LanguageLine extends BaseLanguageLineModel
{
    use HasFactory;

    protected $fillable = [
        'country_lang_code',
        'group',
        'key',
        'text'
    ];
}
