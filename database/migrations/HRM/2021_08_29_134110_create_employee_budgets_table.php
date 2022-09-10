<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id','localID')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('division_id')->nullable();
            $table->foreign('division_id','divsId')->references('id')->on('divisions')->onDelete('cascade');
            $table->unsignedInteger('department_id')->nulable();
            $table->foreign('department_id','depId')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedInteger('section_id')->nullable();
            $table->foreign('section_id','secId')->references('id')->on('sections')->onDelete('cascade');
            $table->unsignedInteger('subsection_id')->nullable();
            $table->foreign('subsection_id','subId')->references('id')->on('subsections')->onDelete('cascade');
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
        Schema::dropIfExists('employee_budgets');
    }
}
