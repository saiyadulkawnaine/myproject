<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbCutpanelRcvQtiesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return  
  */
 public function up()
 {
  Schema::create('so_emb_cutpanel_rcv_qties', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('so_emb_cutpanel_rcv_order_id');
   $table->foreign('so_emb_cutpanel_rcv_order_id')->references('id')->on('so_emb_cutpanel_rcv_orders')->onDelete('cascade');
   $table->unsignedInteger('so_emb_ref_id');
   $table->foreign('so_emb_ref_id', 'soemb')->references('id')->on('so_emb_refs')->onDelete('cascade');
   $table->unsignedInteger('qty');
   $table->string('design_no');
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
  Schema::dropIfExists('so_emb_cutpanel_rcv_qties');
 }
}
