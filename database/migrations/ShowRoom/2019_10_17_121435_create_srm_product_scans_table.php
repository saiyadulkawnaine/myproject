<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSrmProductScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srm_product_scans', function (Blueprint $table) {
            $table->increments('id');
            //$table->unsignedInteger('bar_code_no');
            $table->integer('srm_product_sale_id')->unsigned();
            $table->foreign('srm_product_sale_id')->references('id')->on('srm_product_sales')->onDelete('cascade');
            $table->integer('srm_product_receive_dtl_id')->unsigned();
            $table->foreign('srm_product_receive_dtl_id','receivedtl')->references('id')->on('srm_product_receive_dtls')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->decimal('sales_rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('vat_per',10,6)->nullable();
            $table->decimal('source_tax_per',10,6)->nullable();
            $table->decimal('gross_amount',14,4)->nullable();

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
        Schema::dropIfExists('srm_product_scans');
    }
}
