<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccEdfPaymentsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('acc_edf_payments', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('imp_liability_adjust_chld_id');
   $table->foreign('imp_liability_adjust_chld_id')->references("id")->on('imp_liability_adjust_chlds')->onDelete('cascade');
   $table->date('payment_date');
   $table->decimal('amount', 14, 4)->nullable();
   $table->decimal('interest_amount', 14, 4)->nullable(); 
   $table->decimal('other_charge_amount', 14, 4)->nullable();
   $table->decimal('delay_charge_amount', 14, 4)->nullable();
   $table->unsignedInteger('payment_source_id');//commercial head id
   $table->string('remarks',500)->nullable();
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
  Schema::dropIfExists('acc_edf_payments');
 }
}
