<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('sales_orders', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('job_id');
   $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
   $table->unsignedInteger('projection_id')->nullable();
   $table->string('sale_order_no', 200);
   $table->date('place_date');
   $table->date('receive_date');
   $table->date('ship_date');
   $table->unsignedInteger('produced_company_id')->nullable();
   $table->string('file_no', 100);
   $table->string('internal_ref', 100);
   $table->string('remarks', 500)->nullable();
   $table->unsignedTinyInteger('order_status')->nullable()->default(1);
   $table->unsignedSmallInteger('tna_to')->nullable();
   $table->unsignedSmallInteger('tna_from')->nullable();
   $table->unsignedInteger('qty')->nullable();
   $table->decimal('rate', 12, 4)->nullable();
   $table->decimal('amount', 12, 4)->nullable();
   $table->date('org_ship_date');
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
  Schema::dropIfExists('sales_orders');
 }
}
