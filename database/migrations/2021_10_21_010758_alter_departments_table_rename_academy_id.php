<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDepartmentsTableRenameAcademyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function(Blueprint $table) {
            $table->renameColumn('academy_id', 'company_id');
            $table->dropForeign('departments_academy_id_foreign');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
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
        Schema::table('departments', function(Blueprint $table) {
            $table->renameColumn('company_id', 'academy_id');
            $table->dropForeign('departments_company_id_foreign');
            $table->dropIndex('departments_company_id_foreign');
            $table->foreign('academy_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }
}
