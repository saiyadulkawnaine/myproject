<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHRsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('employee_h_rs', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('user_id')->nulable();
   $table->Integer('company_id')->unsigned();
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->string('name', 100);
   $table->unsignedInteger('code')->unique()->nulable();
   $table->unsignedInteger('empcode_jibika');
   $table->unsignedInteger('department_id')->nulable();
   $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
   $table->unsignedInteger('designation_id');
   $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
   $table->string('grade', 30)->nulable();
   $table->date('date_of_join');
   $table->date('date_of_birth');
   $table->date('status_date')->nullable();
   $table->date('inactive_date')->nullable();
   $table->string('national_id', 100);
   $table->string('address', 255);
   $table->decimal('salary', 14, 4);
   $table->decimal('compliance_salary', 14, 4);
   $table->unsignedTinyInteger('is_advanced_applicable')->nullable();
   $table->unsignedTinyInteger('employee_type_id')->nullable();
   $table->string('last_education', 100)->nulable();
   $table->string('experience', 500)->nullable();
   $table->string('tin', 50)->nulable();
   $table->string('contact', 100);
   $table->string('email', 200)->nulable();
   $table->string('religion', 200)->nulable();
   $table->unsignedTinyInteger('gender_id');
   //$table->unsignedInteger('status_id');

   $table->unsignedInteger('location_id')->nullable();
   $table->unsignedInteger('division_id')->nullable();
   $table->unsignedInteger('section_id')->nullable();
   $table->unsignedInteger('subsection_id')->nullable();
   $table->unsignedInteger('report_to_id')->nullable();
   $table->unsignedInteger('probation_days');
   $table->string('seperation_clause', 1000)->nullable();

   $table->string('transport', 500)->nulable();
   $table->string('group_insurance', 300)->nulable();
   $table->string('utility_bill', 350)->nulable();
   $table->string('allowance', 350)->nulable();
   $table->unsignedInteger('signatory_id')->nullable();
   $table->date('appointment_date')->nullable();
   $table->unsignedTinyInteger('api_status')->nulable()->default(0);

   $table->unsignedSmallInteger('approved_by')->nullable();
   $table->timestamp('approved_at')->nullable();
   $table->unsignedSmallInteger('created_by')->nulable();
   $table->timestamp('created_at')->nulable();
   $table->unsignedSmallInteger('updated_by')->nulable();
   $table->timestamp('updated_at')->nulable();
   $table->timestamp('deleted_at')->nulable();
   $table->string('created_ip', 20)->nulable();
   $table->string('updated_ip', 20)->nulable();
   $table->string('deleted_ip', 20)->nulable();
   $table->unsignedTinyInteger('row_status')->nulable()->default(1);
  });
 }

 /**
  * Reverse the migrations.
  *
  * @return void
  */
 public function down()
 {
  Schema::dropIfExists('employee_h_rs');
 }
}
