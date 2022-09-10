<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitDlvsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('prod_knit_dlvs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('dlv_no');
   $table->date('dlv_date');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id')->references('id')->on('companies');
   $table->unsignedInteger('store_id');
   $table->foreign('store_id')->references('id')->on('stores');
   $table->unsignedInteger('buyer_id')->nullable();
   $table->foreign('buyer_id')->references('id')->on('buyers');
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
  Schema::dropIfExists('prod_knit_dlvs');
 }
}
