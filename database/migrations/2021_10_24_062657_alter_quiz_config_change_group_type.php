<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Doctrine\DBAL\Types\{StringType, Type};
use Illuminate\Support\Facades\{DB, Log};

class AlterQuizConfigChangeGroupType extends Migration
{

    /**
     * ExtendedMigration constructor.
     * Handle Laravel Issue related with modifying tables with enum columns
     */
    public function __construct()
    {
        try {
            Type::hasType('enum') ?: Type::addType('enum', StringType::class);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_configs', function (Blueprint $table) {
            $table->string('group')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_configs', function (Blueprint $table) {
            $table->enum('group', [
                \App\Models\QuizConfig::GROUP_POPUP,
                \App\Models\QuizConfig::GROUP_TEXT
            ])->change();
        });
    }
}
