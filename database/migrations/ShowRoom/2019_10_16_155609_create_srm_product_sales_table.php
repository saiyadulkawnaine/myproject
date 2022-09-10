<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrmProductSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srm_product_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_no');
            $table->unsignedInteger('debit_card_no')->nullable();
            $table->unsignedInteger('credit_card_no')->nullable();
            //$table->decimal('tax_amount',14,4)->nullable();
            $table->decimal('discount_amount',12,4)->nullable();
            $table->decimal('paid_amount',14,4);
            $table->decimal('return_amount',14,4)->nullable();
            //$table->unsignedInteger('payment_type_id')->nullable();
            $table->date('scan_date')->nullable();
            $table->string('customer_name',150)->nullable();
            $table->decimal('net_paid_amount',14,4)->nullable();
            $table->unsignedInteger('credit_sale_id')->nullable();
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('srm_product_sales');
    }
}
