<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdFinishDlvRollsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('prod_finish_dlv_rolls', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('prod_finish_dlv_id');
   $table->foreign('prod_finish_dlv_id', 'dlvprodfinishdlvid')->references('id')->on('prod_finish_dlvs');
   $table->unsignedInteger('prod_batch_finish_qc_roll_id')->unique();
   $table->foreign('prod_batch_finish_qc_roll_id', 'prodbatchfinishqcrollid')->references('id')->on('prod_batch_finish_qc_rolls');
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
  Schema::dropIfExists('prod_finish_dlv_rolls');
 }
}
