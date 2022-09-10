<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJhuteSaleDlvOrdersTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('jhute_sale_dlv_orders', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('do_no');
   $table->unsignedInteger('do_for');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->unsignedInteger('location_id');
   $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
   $table->date('do_date');
   $table->unsignedInteger('currency_id');
   $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
   $table->date('etd_date')->nullable();
   $table->unsignedInteger('buyer_id')->nullable();
   $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
   $table->unsignedInteger('advised_by_id');
   $table->unsignedInteger('price_verified_by_id');
   $table->unsignedTinyInteger('payment_before_dlv_id');
   $table->string('remarks', 500)->nullable();
   $table->unsignedSmallInteger('status_id');
   $table->unsignedTinyInteger('ready_to_approve_id');
   $table->unsignedSmallInteger('approved_by')->nullable();
   $table->timestamp('approved_at')->nullable();
   $table->unsignedSmallInteger('unapproved_by')->nullable();
   $table->timestamp('unapproved_at')->nullable();
   $table->unsignedSmallInteger('unapproved_count')->nullable();
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
  Schema::dropIfExists('jhute_sale_dlv_orders');
 }
}
