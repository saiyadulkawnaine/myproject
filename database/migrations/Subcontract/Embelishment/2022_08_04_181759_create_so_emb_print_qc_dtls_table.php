<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintQcDtlsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_qc_dtls', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('prod_source_id');
   $table->unsignedInteger('so_emb_print_qc_id');
   $table->foreign('so_emb_print_qc_id')->references('id')->on('so_emb_print_qcs');
   $table->unsignedInteger('so_emb_ref_id');
   $table->foreign('so_emb_ref_id')->references('id')->on('so_emb_refs');
   $table->string('prod_hour', 11)->nullable();
   $table->unsignedInteger('qc_pass');
   $table->unsignedInteger('reject');
   $table->unsignedInteger('alter');
   $table->unsignedInteger('replace');
   $table->string('remarks', 200)->nullable();

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
  Schema::dropIfExists('so_emb_print_qc_dtls');
 }
}
