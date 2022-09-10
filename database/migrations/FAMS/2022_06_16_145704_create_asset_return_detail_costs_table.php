<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetReturnDetailCostsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('asset_return_detail_costs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('asset_return_detail_id');
   $table->foreign('asset_return_detail_id')->references('id')->on('asset_return_details')->onDelete('cascade');
   $table->string('cost_component', 200)->nullable();
   $table->decimal('qty', 12, 4);
   $table->decimal('rate', 12, 4);
   $table->decimal('amount', 14, 4);
   $table->decimal('discount', 12, 4);
   $table->decimal('net_cost', 14, 4);

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
  Schema::dropIfExists('asset_return_detail_costs');
 }
}
