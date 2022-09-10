<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrmProductReceiveDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srm_product_receive_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('srm_product_receive_id')->unsigned();
            $table->foreign('srm_product_receive_id')->references('id')->on('srm_product_receives')->onDelete('cascade');
            $table->unsignedInteger('style_id');
            $table->string('style_ref',150);
            $table->unsignedInteger('style_gmt_id')->nullable();
            $table->string('style_gmt_name')->nullable();
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('sales_order_id');
            $table->string('sale_order_no');
            $table->unsignedInteger('size_id')->nullable();
            $table->string('size_name')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->string('color_name')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('sales_order_gmt_color_size_id');
            $table->unsignedInteger('uom_id')->nullable();
            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',10,6)->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('sales_rate',14,4)->nullable();
            $table->decimal('vat_per',10,6)->nullable();
            $table->decimal('source_tax_per',10,6)->nullable();
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
        Schema::dropIfExists('srm_product_receive_dtls');
    }
}
