<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAffiliateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_affiliate', function (Blueprint $table) {
            $table->foreignId('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreignId('affiliate_id')
                ->references('id')
                ->on('affiliates')
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
        Schema::dropIfExists('course_affiliate');
    }
}
