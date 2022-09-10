<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentives', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->unsignedInteger('location_id')->unsigned();
          $table->unsignedInteger('division_id')->unsigned();
          $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
          $table->unsignedInteger('department_id')->unsigned();
          $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
          $table->unsignedInteger('section_id')->unsigned();
          $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
          $table->unsignedInteger('production_process_id')->unsigned();
          $table->foreign('production_process_id')->references('id')->on('production_processes')->onDelete('cascade');
          $table->unsignedTinyInteger('basis_id');
          $table->unsignedInteger('designation_id');
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
        Schema::dropIfExists('incentives');
    }
}
