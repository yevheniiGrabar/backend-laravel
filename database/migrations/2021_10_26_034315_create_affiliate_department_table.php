<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_department', function (Blueprint $table) {
            $table->foreignId('affiliate_id')
                ->references('id')
                ->on('affiliates')
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();

            $table->unique(['affiliate_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_department');
    }
}
