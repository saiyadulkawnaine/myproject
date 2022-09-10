<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbCutpanelRcvsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_cutpanel_rcvs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->unsignedInteger('buyer_id');
   $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
   $table->unsignedInteger('shift_id');
   $table->date('receive_date');
   $table->unsignedInteger('production_area_id');
   $table->string('challan_no', 50);
   $table->unsignedTinyInteger('is_self');
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
  Schema::dropIfExists('so_emb_cutpanel_rcvs');
 }
}
