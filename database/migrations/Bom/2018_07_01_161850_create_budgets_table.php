<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('job_id')->unique();
          $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
          $table->unsignedInteger('style_id');
          $table->unsignedTinyInteger('costing_unit_id');
		      $table->date('budget_date');
          $table->string('remarks',500)->nullable();
          $table->unsignedInteger('approved_by')->nullable();
          $table->timestamp('approved_at')->nullable();
          $table->unsignedInteger('unapproved_by')->nullable();
          $table->timestamp('unapproved_at')->nullable();
          $table->unsignedInteger('unapproved_count')->nullable();
          $table->unsignedSmallInteger('created_by')->nullable();
          $table->timestamp('created_at')->nullable();
          $table->unsignedSmallInteger('updated_by')->nullable();
          $table->timestamp('updated_at')->nullable();
          
          $table->timestamp('deleted_at')->nullable();
          $table->string('created_ip',20)->nullable();
          $table->string('updated_ip',20)->nullable();
          $table->string('deleted_ip',20)->nullable();
          $table->unsignedTinyInteger('row_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
