<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderClosesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('sales_order_closes', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('sale_order_id');
   $table->foreign('sale_order_id', 'saleordercloseid')->references('id')->on('sales_orders')->onDelete('cascade');
   $table->date('request_date');
   $table->date('closed_date')->nullable();
   $table->string('remarks', 600)->nullable();
   $table->unsignedInteger('approved_by')->nullable();
   $table->timestamp('approved_at')->nullable();
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
  Schema::dropIfExists('sales_order_closes');
 }
}
