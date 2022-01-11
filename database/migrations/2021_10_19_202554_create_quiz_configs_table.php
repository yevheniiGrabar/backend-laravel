<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_configs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('group', [
                \App\Models\QuizConfig::GROUP_POPUP,
                \App\Models\QuizConfig::GROUP_TEXT
            ]);
            $table->foreignId('quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onDelete('cascade');
            $table->boolean('enabled')
                ->default(false)
                ->comment('it\'s a state for checkboxes, for example `Show popup on quiz pass`');
            $table->text('value')
                ->nullable()
                ->comment('here we can store custom messages etc, like `Welcome message` or `Pass message`');
            $table->timestamps();
            $table->unique(['code', 'quiz_id', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_configs');
    }
}
