<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('title', 100)->change();
            $table->dropColumn('media');
            $table->string('logo', 100)->nullable(true)->after('status')->comment("storage path");
            $table->foreignId('department_id')->comment("IT/Sales/HR ...")->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table('courses', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->dropColumn('logo');
            $table->text('media');
            DB::table('courses')->whereNull('department_id')->delete();
            $table->foreignId('department_id')->nullable(false)->change();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
