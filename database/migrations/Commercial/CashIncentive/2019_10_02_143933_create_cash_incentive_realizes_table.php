<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveRealizesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('cash_incentive_realizes', function (Blueprint $table) {
   $table->increments('id');
   $table->integer('cash_incentive_ref_id')->unsigned();
   $table->foreign('cash_incentive_ref_id')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
   $table->decimal('sanctioned_amount', 14, 4);
   $table->string('remarks', 500)->nullable();
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
  Schema::dropIfExists('cash_incentive_realizes');
 }
}
