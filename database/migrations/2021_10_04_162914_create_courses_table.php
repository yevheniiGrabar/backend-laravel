<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
            $table->foreignId('academy_id')
                ->references('id')
                ->on('academies')
                ->onDelete('cascade');
            $table->text('media')->comment('Image or Video');
            $table->string('title');
            $table->unique(['academy_id', 'department_id', 'title']);
            $table->text('description');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
