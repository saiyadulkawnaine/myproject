<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetQuantityCostsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('asset_quantity_costs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('asset_acquisition_id')->unsigned();
   $table->foreign('asset_acquisition_id')->references('id')->on('asset_acquisitions')->onDelete('cascade');
   $table->string('serial_no', 100)->nullable();
   $table->unsignedInteger('qty')->nullable();
   $table->decimal('rate', 12, 4)->nullable();
   $table->decimal('vendor_price', 12, 4)->nullable();
   $table->decimal('landed_price', 12, 4)->nullable();
   $table->decimal('machanical_cost', 12, 4)->nullable();
   $table->decimal('civil_cost', 12, 4)->nullable();
   $table->decimal('electrical_cost', 12, 4)->nullable();
   $table->decimal('total_cost', 12, 4)->nullable();
   $table->date('warrantee_close')->nullable();
   $table->unsignedInteger('asset_no')->nullable();
   $table->string('custom_no', 100)->nullable();
   $table->decimal('accumulated_dep', 12, 4)->nullable();
   $table->decimal('salvage_value', 12, 4)->nullable();
   $table->unsignedSmallInteger('life_time')->nullable();
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
  Schema::dropIfExists('quantity_costs');
 }
}
