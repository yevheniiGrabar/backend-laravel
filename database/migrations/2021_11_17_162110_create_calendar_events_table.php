<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('summary');
            $table->string('calendar_id');
            $table->string('status')->default(\App\Enums\Calendar\CalendarEventStatusEnum::STATUS_CONFIRMED);
            $table->string('transparency')->default(\App\Enums\Calendar\CalendarEventTransparencyEnum::TRANSP_TRANSPARENT);
            $table->string('visibility')->default(\App\Enums\Calendar\CalendarEventVisibilityEnum::PUBLIC);
            $table->string('color')->nullable();
            $table->string('icaluid')->nullable();
            $table->string('parent_id')->nullable();

            $table->boolean('is_all_day')->default(0);

            $table->unsignedInteger('sequence')->default(0);

            $table->text('description')->nullable();

            $table->timestampTz('dt_start')->nullable();
            $table->timestampTz('dt_end')->nullable();
            $table->timestamps();
        });

        Schema::table('calendar_events', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('calendar_events')
                ->onDelete('cascade');

            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendars')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}
