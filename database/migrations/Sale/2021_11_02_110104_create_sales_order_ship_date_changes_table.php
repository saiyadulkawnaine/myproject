<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderShipDateChangesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('sales_order_ship_date_changes', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('sale_order_id');
   $table->foreign('sale_order_id', 'saleorderid')->references('id')->on('sales_orders')->onDelete('cascade');
   $table->date('ship_date');
   $table->date('old_ship_date');
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
  Schema::dropIfExists('sales_order_ship_date_changes');
 }
}
