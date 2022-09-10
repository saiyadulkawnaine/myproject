<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccCostDistributionDtlsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('acc_cost_distribution_dtls', function (Blueprint $table) {
   $table->increments('id');
   $table->unsignedInteger('acc_cost_distribution_id')->nullable();
   $table->foreign('acc_cost_distribution_id')->references('id')->on('acc_cost_distributions')->onDelete('cascade');
   $table->unsignedInteger('cost_type_id');
   $table->unsignedInteger('sale_order_id');
   $table->foreign('sale_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
   $table->decimal('amount', 14, 4);
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
  Schema::dropIfExists('acc_cost_distribution_dtls');
 }
}
