<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetAcquisitionsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('asset_acquisitions', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('company_id')->unsigned();
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->unsignedInteger('location_id')->unsigned();
   $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
   $table->unsignedInteger('division_id');
   $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
   $table->unsignedInteger('department_id');
   $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
   $table->unsignedInteger('section_id');
   $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
   $table->unsignedInteger('subsection_id');
   $table->foreign('subsection_id')->references('id')->on('subsections')->onDelete('cascade');
   $table->string('name', 400);
   $table->unsignedSmallInteger('type_id');
   $table->string('asset_group');
   //if asset type is machinary than production area
   $table->unsignedTinyInteger('production_area_id')->nullable();
   $table->unsignedInteger('store_id')->nullable()->unsigned();
   $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
   $table->unsignedInteger('supplier_id')->unsigned();
   $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
   $table->string('iregular_supplier')->nullable();
   $table->string('brand')->nullable();
   $table->string('origin')->nullable();
   $table->date('purchase_date')->nullable();
   $table->unsignedInteger('qty')->nullable(); //no decimal
   $table->decimal('accumulated_dep', 12, 4)->nullable();
   $table->decimal('salvage_value', 12, 4)->nullable();
   $table->unsignedSmallInteger('depreciation_method_id')->nullable();
   $table->decimal('depreciation_rate', 12, 4)->nullable();
   $table->unsignedSmallInteger('life_time')->nullable();
   $table->unsignedInteger('prod_capacity')->nullable();

   $table->unsignedInteger('uom_id')->nullable();
   $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
   $table->unsignedSmallInteger('sort_id')->nullable(); //sequence_no
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
  Schema::dropIfExists('asset_acquisitions');
 }
}
