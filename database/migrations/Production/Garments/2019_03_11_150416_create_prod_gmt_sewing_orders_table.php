<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtSewingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_sewing_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_gmt_sewing_id')->unsigned();
            $table->foreign('prod_gmt_sewing_id')->references('id')->on('prod_gmt_sewings')->onDelete('cascade');
            $table->integer('sales_order_country_id')->unsigned();
            $table->foreign('sales_order_country_id')->references('id')->on('sales_order_countries')->onDeletes('cascade');
            $table->integer('style_gmt_id')->unsigned();
            $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->unsignedSmallInteger('prod_source_id')->nullable();
            $table->unsignedSmallInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('prod_hour', 11)->nullable();
            $table->timestamp('prod_at')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->integer('wstudy_line_setup_id')->unsigned();
            $table->foreign('wstudy_line_setup_id')->references('id')->on('wstudy_line_setups')->onDelete('cascade');
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
        Schema::dropIfExists('prod_gmt_sewing_orders');
    }
}
