<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleGmtsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('style_gmts', function (Blueprint $table) {

   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('style_id')->unsigned();
   $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
   $table->unsignedInteger('item_account_id')->unsigned();
   $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
   $table->string('article', 100)->nullable();
   $table->unsignedInteger('gmt_qty');
   $table->unsignedSmallInteger('item_complexity');
   $table->string('custom_catg', 50)->nullable();
   $table->unsignedSmallInteger('gmt_catg')->nullable();
   $table->decimal('smv', 8, 4)->nullable();
   $table->decimal('sewing_effi_per', 8, 4)->nullable();
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
  Schema::dropIfExists('style_gmts');
 }
}
