<?php

use App\Models\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->text('help')
                ->nullable()
                ->comment('Moderator help text');
            $table->enum('type', [
                Quiz::TYPE_CHOICE,
                Quiz::TYPE_FILE,
                Quiz::TYPE_MULTIPLE_CHOICE,
                Quiz::TYPE_RECORD,
                Quiz::TYPE_TEXT
            ]);
            $table->foreignId('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
