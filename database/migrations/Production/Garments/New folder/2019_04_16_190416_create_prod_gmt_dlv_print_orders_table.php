<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtDlvPrintOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_dlv_print_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_gmt_dlv_print_id')->unsigned();
            $table->foreign('prod_gmt_dlv_print_id')->references('id')->on('prod_gmt_dlv_prints')->onDelete('cascade');
            $table->integer('sales_order_country_id')->unsigned();
            $table->foreign('sales_order_country_id')->references('id')->on('sales_order_countries')->onDeletes('cascade');
            $table->unsignedSmallInteger('fabric_look_id')->nullable();
           
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
        Schema::dropIfExists('prod_gmt_dlv_print_orders');
    }
}
