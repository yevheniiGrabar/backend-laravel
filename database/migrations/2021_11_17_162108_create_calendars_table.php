<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('summary');
            $table->string('timezone')->default(\App\Enums\Calendar\CalendarTimeZoneEnum::EUROPE_KIEV);
            $table->string('scale')->default(\App\Enums\Calendar\CalscaleEnum::CALSCALE_GREGORIAN);
            $table->string('provider')->default(\App\Enums\Calendar\CalendarProviderEnum::INTERNAL);
            $table->string('owner_type')->default(addslashes(\App\Models\User::class));

            $table->unsignedInteger('owner_id');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['owner_type', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
