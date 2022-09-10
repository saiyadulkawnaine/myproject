<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpInvoiceOrderDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_invoice_order_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_invoice_order_id')->unsigned();
            $table->foreign('exp_invoice_order_id')->references('id')->on('exp_invoice_orders')->onDelete('cascade');
            $table->integer('sales_order_gmt_color_size_id')->unsigned();
            $table->foreign('sales_order_gmt_color_size_id','saleordersgmtcolorsizeid')->references('id')->on('sales_order_gmt_color_sizes')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);
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
        Schema::dropIfExists('exp_invoice_order_dtls');
    }
}
