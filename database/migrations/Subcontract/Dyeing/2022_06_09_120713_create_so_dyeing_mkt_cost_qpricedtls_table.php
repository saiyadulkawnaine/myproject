<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingMktCostQpricedtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_mkt_cost_qpricedtls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_dyeing_mkt_cost_qprice_id');
            $table->foreign('so_dyeing_mkt_cost_qprice_id','QpriceID')->references('id')->on('so_dyeing_mkt_cost_qprices')->onDelete('cascade');
            $table->unsignedInteger('so_dyeing_mkt_cost_fab_id');
            $table->foreign('so_dyeing_mkt_cost_fab_id','QuotedPriceFabricID')->references('id')->on('so_dyeing_mkt_cost_fabs')->onDelete('cascade');
            $table->decimal('cost_per_kg',10,4);
            $table->decimal('quoted_price_bdt',14,4);
            $table->decimal('quoted_price',14,4);
            $table->decimal('profit_amount_bdt',14,4);
            $table->decimal('profit_amount',14,4);
            $table->decimal('profit_per',10,4);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('so_dyeing_mkt_cost_qpricedtls');
    }
}
