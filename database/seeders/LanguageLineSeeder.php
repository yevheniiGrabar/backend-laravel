<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $data = [
            'default_error' => [
                'group' => 'validation',
                'key' => 'default_error',
                'text' => json_encode([
                    'en' => 'The given data was invalid.',
                    'ru' => 'Указанные данные недействительны.'
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'required' => [
                'group' => 'validation',
                'key' => 'required',
                'text' => json_encode([
                    'en' => 'The :field field is required.',
                    'ru' => 'Поле :field обязательное.'
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'exists' => [
                'group' => 'validation',
                'key' => 'exists',
                'text' => json_encode([
                    'en' => 'The selected :field is invalid.',
                    'ru' => 'Выбранное поле :field неправильное.'
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'min' => [
                'group' => 'validation',
                'key' => 'min',
                'text' => json_encode([
                    'en' => 'The :field must be at least :additional1 characters.',
                    'ru' => 'Поле :field должно состоять минимум из :additional1 символов.'
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'max' => [
                'group' => 'validation',
                'key' => 'max',
                'text' => json_encode([
                    'en' => 'The :field must not be greater than :additional1 characters.',
                    'ru' => 'Поле :field не должно быть длиннее чем :additional1 символов.'
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($data as $key => $arr) {
            if (!DB::table('language_lines')->where('key', $key)->exists()) {
                DB::table('language_lines')->insert($arr);
            }
        }
    }
}
