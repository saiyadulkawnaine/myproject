<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintDlvItemsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_dlv_items', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('so_emb_print_dlv_id');
   $table->foreign('so_emb_print_dlv_id','empprint')->references('id')->on('so_emb_print_dlvs')->onDelete('cascade');
   $table->unsignedInteger('so_emb_cutpanel_rcv_qty_id');
   $table->foreign('so_emb_cutpanel_rcv_qty_id','embcutpanel')->references('id')->on('so_emb_cutpanel_rcv_qties')->onDelete('cascade');
   $table->decimal('dlv_qty', 12, 4);
   $table->decimal('additional_charge', 12, 4);
   $table->decimal('amount', 14, 4);
   $table->string('delivery_point');

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
  Schema::dropIfExists('so_emb_print_dlv_items');
 }
}
