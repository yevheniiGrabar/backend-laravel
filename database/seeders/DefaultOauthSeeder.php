<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DefaultOauthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('oauth_clients')->count()) {
            Artisan::call('passport:install');
        }

        DB::table('oauth_clients')
            ->whereId(Env::get('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))
            ->update(['secret' => Env::get('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET')]);

        DB::table('oauth_clients')
            ->whereId(Env::get('PASSPORT_PASSWORD_GRANT_CLIENT_ID'))
            ->update(['secret' => Env::get('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET')]);
    }
}
