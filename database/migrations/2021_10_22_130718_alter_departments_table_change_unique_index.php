<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDepartmentsTableChangeUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique('departments_title_unique');

            $table->unique(['company_id', 'title'], 'departments_company_id_title_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            DB::table('departments')->delete();
            $table->dropForeign('departments_company_id_foreign');
            $table->dropUnique('departments_company_id_title_unique');
            $table->unique(['title'], 'departments_title_unique');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }
}
