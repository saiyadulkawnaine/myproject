<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetServiceDetailsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('asset_service_details', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('asset_service_id');
   $table->foreign('asset_service_id')->references('id')->on('asset_services')->onDelete('cascade');
   $table->unsignedInteger('asset_quantity_cost_id');
   $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs')->onDelete('cascade');
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
  Schema::dropIfExists('asset_service_details');
 }
}
