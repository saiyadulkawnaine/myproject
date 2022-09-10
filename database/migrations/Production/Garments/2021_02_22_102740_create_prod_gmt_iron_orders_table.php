<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtIronOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_iron_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_gmt_iron_id')->unsigned();
            $table->foreign('prod_gmt_iron_id')->references('id')->on('prod_gmt_irons')->onDelete('cascade');
            $table->integer('sales_order_country_id','ironsalesordercountry')->unsigned();
            $table->foreign('sales_order_country_id')->references('id')->on('sales_order_countries')->onDeletes('cascade');
            $table->unsignedSmallInteger('prod_source_id')->nullable();
            $table->unsignedInteger('supplier_id','supplierid')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('prod_hour',11)->nullable();
            $table->unsignedInteger('location_id')->nullable();
            // $table->integer('wstudy_line_setup_id','ironwstudylinesetup')->nullable()->unsigned();
            // $table->foreign('wstudy_line_setup_id')->references('id')->on('wstudy_line_setups')->onDelete('cascade');
            $table->integer('asset_quantity_cost_id')->nullable()->unsigned();
            $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs')->onDelete('cascade');
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
        Schema::dropIfExists('prod_gmt_iron_orders');
    }
}
