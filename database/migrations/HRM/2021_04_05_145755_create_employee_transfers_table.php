<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id','eId')->references('id')->on('employee_h_rs')->onDelete('cascade');
            
            $table->date('transfer_date')->nulable();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id','companyId')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('code');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id','locactionId')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('division_id');
            $table->foreign('division_id','divisionId')->references('id')->on('divisions')->onDelete('cascade');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id','departmentId')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedInteger('section_id');
            $table->foreign('section_id','sectionId')->references('id')->on('sections')->onDelete('cascade');
            $table->unsignedInteger('subsection_id');
            $table->foreign('subsection_id','subsectionId')->references('id')->on('subsections')->onDelete('cascade');
            $table->unsignedInteger('report_to_id');


            $table->unsignedInteger('old_company_id');
            $table->foreign('old_company_id','oldcompanyId')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('old_code');
            $table->unsignedInteger('old_location_id');
            $table->foreign('old_location_id','oldlocactionId')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('old_division_id');
            $table->foreign('old_division_id','olddivisionId')->references('id')->on('divisions')->onDelete('cascade');
            $table->unsignedInteger('old_department_id');
            $table->foreign('old_department_id','olddepartmentId')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedInteger('old_section_id');
            $table->foreign('old_section_id','oldsectionId')->references('id')->on('sections')->onDelete('cascade');
            $table->unsignedInteger('old_subsection_id');
            $table->foreign('old_subsection_id','oldsubsectionId')->references('id')->on('subsections')->onDelete('cascade');
            $table->unsignedInteger('old_report_to_id');
            $table->string('remarks',400)->nullable();
            $table->unsignedTinyInteger('api_status')->nulable()->default(0);
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
        Schema::dropIfExists('employee_transfers');
    }
}
