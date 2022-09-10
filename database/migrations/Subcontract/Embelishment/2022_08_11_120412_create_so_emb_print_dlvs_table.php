<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbPrintDlvsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('so_emb_print_dlvs', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('dlv_no');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id', 'comid')->references('id')->on('companies');
   $table->unsignedInteger('buyer_id');
   $table->foreign('buyer_id', 'buyerid')->references('id')->on('buyers');
   $table->unsignedInteger('production_area_id');
   $table->unsignedInteger('currency_id');
   $table->foreign('currency_id', 'currencyid')->references('id')->on('currencies');
   $table->date('dlv_date');
   $table->string('driver_name', 200)->nullable();
   $table->string('driver_contact_no', 150)->nullable();
   $table->string('driver_license_no', 200)->nullable();
   $table->string('lock_no', 200)->nullable();
   $table->string('truck_no', 100)->nullable();
   $table->string('remarks', 500)->nullable();

   $table->unsignedSmallInteger('approved_by')->nullable();
   $table->timestamp('approved_at')->nullable();
   $table->unsignedSmallInteger('unapproved_by')->nullable();
   $table->timestamp('unapproved_at')->nullable();
   $table->unsignedSmallInteger('unapproved_count')->nullable();

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
  Schema::dropIfExists('so_emb_print_dlvs');
 }
}
