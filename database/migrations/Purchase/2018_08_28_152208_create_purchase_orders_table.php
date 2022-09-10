<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
		      $table->unsignedInteger('pur_order_no');
          $table->date('pur_order_date')->nullable();
		      $table->unsignedTinyInteger('order_type_id');
          $table->unsignedInteger('company_id');
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->unsignedTinyInteger('source_id');
		      $table->unsignedTinyInteger('basis_id')->nullable();
          $table->unsignedInteger('supplier_id');
          $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
          $table->unsignedInteger('currency_id');
          $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
		      $table->decimal('exch_rate', 8, 4);
          $table->date('delv_start_date')->nullable();
          $table->date('delv_end_date')->nullable();
          $table->unsignedTinyInteger('pay_mode');
          $table->string('pi_no',50)->nullable();
          $table->date('pi_date')->nullable();
          $table->string('remarks',500)->nullable();
          $table->decimal('amount', 14, 4);
          $table->unsignedInteger('itemcategory_id')->nullable();
         
          $table->unsignedSmallInteger('created_by')->nullable();
          $table->timestamp('created_at')->nullable();
          $table->unsignedSmallInteger('updated_by')->nullable();
          $table->timestamp('updated_at')->nullable();
          $table->timestamp('deleted_at')->nullable();
          $table->string('created_ip',20)->nullable();
          $table->string('updated_ip',20)->nullable();
          $table->string('deleted_ip',20)->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
