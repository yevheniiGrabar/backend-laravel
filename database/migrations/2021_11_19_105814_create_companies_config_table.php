<?php

use App\Enums\CompanyConfig\CountryEnum;
use App\Enums\CompanyConfig\LocaleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('country')
                ->default(CountryEnum::UKRAINE)
                ->after('owner_id');
            $table->string('locale')
                ->default(LocaleEnum::RU)
                ->after('country');
            $table->string('color')
                ->nullable()
                ->after('locale');
            $table->string('logo')
                ->nullable()
                ->after('color');
            $table->string('domain')
                ->nullable()
                ->after('locale');

            $table->boolean('is_multi_country')
                ->default(false)
                ->after('domain');

            $table->json('buttons_config')
                ->nullable()
                ->after('is_multi_country');
            $table->json('font_config')
                ->nullable()
                ->after('buttons_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function(Blueprint $table) {
            $table->dropColumn([
                'country',
                'locale',
                'color',
                'logo',
                'domain',
                'is_multi_country',
                'buttons_config',
                'font_config'
            ]);
        });
    }
}
