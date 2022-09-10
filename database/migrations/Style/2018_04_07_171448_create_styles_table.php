<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('styles', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('buyer_id')->unsigned();
   $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
   $table->date('receive_date');
   $table->string('style_ref', 50);
   $table->string('style_description', 150)->nullable();
   $table->unsignedSmallInteger('dept_category_id');
   $table->string('product_code', 100)->nullable();
   $table->unsignedInteger('productdepartment_id')->unsigned();
   $table->foreign('productdepartment_id')->references('id')->on('productdepartments')->onDelete('cascade');
   $table->unsignedInteger('season_id')->unsigned();
   $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
   $table->unsignedInteger('uom_id')->unsigned();
   $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
   $table->unsignedInteger('offer_qty')->nullable();
   $table->date('ship_date')->nullable();
   $table->string('buyer_ref', 100)->nullable();
   $table->unsignedInteger('team_id')->unsigned();
   $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
   $table->unsignedInteger('teammember_id')->unsigned();
   $table->foreign('teammember_id')->references('id')->on('teammembers')->onDelete('cascade');
   $table->unsignedInteger('factory_merchant_id')->unsigned()->nullable();
   $table->unsignedInteger('buying_agent_id')->nullable();
   $table->unsignedInteger('flie_src')->nullable();
   $table->string('remarks', 255)->nullable();
   $table->string('contact', 255)->nullable();
   $table->unsignedInteger('copied_from')->nullable();

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
  Schema::dropIfExists('styles');
 }
}
