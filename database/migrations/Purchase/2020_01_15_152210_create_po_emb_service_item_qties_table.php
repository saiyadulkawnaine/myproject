<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoEmbServiceItemQtiesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('po_emb_service_item_qties', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('po_emb_service_item_id');
   $table->foreign('po_emb_service_item_id')->references('id')->on('po_emb_service_items')->onDelete('cascade');
   $table->unsignedInteger('budget_emb_con_id');
   $table->foreign('budget_emb_con_id')->references('id')->on('budget_emb_cons')->onDelete('cascade');
   $table->decimal('qty', 12, 4);
   $table->decimal('rate', 12, 4);
   $table->decimal('amount', 12, 4);
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
  Schema::dropIfExists('po_emb_service_item_qties');
 }
}
