<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbCutpanelRcvOrdersTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_cutpanel_rcv_orders', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('so_emb_cutpanel_rcv_id');
   $table->foreign('so_emb_cutpanel_rcv_id')->references('id')->on('so_emb_cutpanel_rcvs')->onDelete('cascade');
   $table->unsignedInteger('so_emb_id');
   $table->foreign('so_emb_id', 'sofdk')->references('id')->on('so_embs')->onDelete('cascade');
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
  Schema::dropIfExists('so_emb_cutpanel_rcv_orders');
 }
}
