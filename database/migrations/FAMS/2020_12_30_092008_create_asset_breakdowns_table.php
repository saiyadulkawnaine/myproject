<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetBreakdownsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('asset_breakdowns', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('asset_quantity_cost_id');
   $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs')->onDelete('cascade');
   $table->timestamp('breakdown_at');
   $table->timestamp('function_at')->nulable();
   $table->unsignedInteger('employee_h_r_id')->nulable();
   $table->foreign('employee_h_r_id', 'estimatedEmpId')->references('id')->on('employee_h_rs')->onDelete('cascade');
   $table->timestamp('estimated_recovery_at')->nulable();
   $table->unsignedSmallInteger('reason_id')->nullable();
   $table->unsignedSmallInteger('decision_id')->nullable();
   $table->string('action_taken', 400)->nullable();
   $table->string('remarks', 400)->nullable();
   $table->unsignedSmallInteger('created_by')->nullable();
   $table->timestamp('created_at')->nullable();
   $table->unsignedSmallInteger('updated_by')->nullable();
   $table->timestamp('updated_at')->nullable();
   $table->timestamp('deleted_at')->nullable();
   $table->string('created_ip', 20)->nullable();
   $table->string('updated_ip', 20)->nullable();
   $table->string('deleted_ip', 20)->nullable();
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
  Schema::dropIfExists('asset_breakdowns');
 }
}
