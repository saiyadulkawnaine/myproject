<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJhuteStockItemsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('jhute_stock_items', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('jhute_stock_id');
   $table->foreign('jhute_stock_id')->references('id')->on('jhute_stocks')->onDelete('cascade');
   $table->unsignedInteger('acc_chart_ctrl_head_id');
   $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads')->onDelete('cascade');
   $table->unsignedInteger('uom_id');
   $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
   $table->decimal('qty', 14, 4);
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
  Schema::dropIfExists('jhute_stock_items');
 }
}
