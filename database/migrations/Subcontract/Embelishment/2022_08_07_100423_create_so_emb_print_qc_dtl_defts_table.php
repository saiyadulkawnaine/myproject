<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintQcDtlDeftsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_qc_dtl_defts', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('so_emb_print_qc_dtl_id');
   $table->foreign('so_emb_print_qc_dtl_id', 'soembdtl')->references('id')->on('so_emb_print_qc_dtls')->onDelete('cascade');
   $table->unsignedInteger('product_defect_id');
   $table->foreign('product_defect_id', 'productdef')->references('id')->on('product_defects')->onDelete('cascade');
   $table->unsignedInteger('no_of_defect');

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
  Schema::dropIfExists('so_emb_print_qc_dtl_defts');
 }
}
