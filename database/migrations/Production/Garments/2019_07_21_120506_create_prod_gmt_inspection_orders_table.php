<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtInspectionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_inspection_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_gmt_inspection_id')->unsigned();
            $table->foreign('prod_gmt_inspection_id')->references('id')->on('prod_gmt_inspections')->onDelete('cascade');
            $table->integer('sales_order_gmt_color_size_id')->unsigned();
            $table->foreign('sales_order_gmt_color_size_id','saleorderinspid')->references('id')->on('sales_order_gmt_color_sizes')->onDelete('cascade');
            $table->unsignedInteger('qty')->nullable();
            $table->unsignedInteger('re_check_qty')->nullable();
            $table->unsignedInteger('failed_qty')->nullable();
            $table->string('re_check_remarks',500)->nullable();
            $table->string('failed_remarks',500)->nullable();
            $table->date('expected_exfactory_date')->nullable();
            $table->unsignedInteger('exfactory_qty')->nullable();
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
        Schema::dropIfExists('prod_gmt_inspection_orders');
    }
}
