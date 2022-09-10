<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJhuteStocksTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('jhute_stocks', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('stock_no');
   $table->unsignedInteger('stock_for');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->unsignedInteger('location_id');
   $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
   $table->date('stock_date');
   $table->string('remarks', 500)->nullable();
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
  Schema::dropIfExists('jhute_stocks');
 }
}
