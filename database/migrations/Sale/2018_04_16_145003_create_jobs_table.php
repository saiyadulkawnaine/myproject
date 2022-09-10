<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('jobs', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('job_no');
   $table->unsignedInteger('company_id');
   $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
   $table->unsignedInteger('style_id')->unique();
   $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
   $table->unsignedInteger('buyer_id');
   $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
   $table->unsignedInteger('currency_id');
   $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
   $table->decimal('exch_rate', 10, 4);
   $table->unsignedInteger('uom_id');
   $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
   $table->unsignedInteger('season_id');
   $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
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
  Schema::dropIfExists('jobs');
 }
}
