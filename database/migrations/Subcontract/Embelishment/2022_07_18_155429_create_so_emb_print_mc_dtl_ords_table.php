<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintMcDtlOrdsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_mc_dtl_ords', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('so_emb_print_mc_dtl_id');
   $table->foreign('so_emb_print_mc_dtl_id','soprintdtl')->references('id')->on('so_emb_print_mc_dtls')->onDelete('cascade');
   $table->unsignedInteger('gmtspart_id');
   $table->unsignedInteger('item_account_id');
   $table->unsignedInteger('so_emb_ref_id');
   $table->foreign('so_emb_ref_id','soembrefid')->references('id')->on('so_emb_refs')->onDelete('cascade');
   $table->decimal('qty', 12, 4);
   $table->decimal('prod_hour', 12, 4);
   $table->string('printing_start_at', 100)->nullable();
   $table->string('printing_end_at', 100)->nullable();
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
  Schema::dropIfExists('so_emb_print_mc_dtl_ords');
 }
}
