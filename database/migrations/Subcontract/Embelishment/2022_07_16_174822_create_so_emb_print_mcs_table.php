<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintMcsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_mcs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('asset_quantity_cost_id');
   $table->foreign("asset_quantity_cost_id")->references('id')->on("asset_quantity_costs")->onDelete('cascade');
   $table->unsignedInteger('company_id');
   $table->foreign("company_id")->references('id')->on("companies")->onDelete('cascade');
   $table->string('remarks', 400);

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
  Schema::dropIfExists('so_emb_print_mcs');
 }
}
