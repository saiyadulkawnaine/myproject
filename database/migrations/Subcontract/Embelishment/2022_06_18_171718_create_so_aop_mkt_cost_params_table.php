<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopMktCostParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_mkt_cost_params', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_aop_mkt_cost_id');
            $table->foreign('so_aop_mkt_cost_id')->references('id')->on('so_aop_mkt_costs')->onDelete('cascade');
            //$table->unsignedInteger('autoyarn_id')->nullable();
            $table->unsignedInteger('colorrange_id');
            $table->unsignedInteger('gsm_weight')->nullable();
            $table->unsignedInteger('dia')->nullable();
            $table->unsignedInteger('print_type_id');
            $table->decimal('fabric_wgt',10,4);
            $table->decimal('paste_wgt',10,4);
            $table->decimal('offer_qty',10,4)->nullable();
            $table->decimal('color_ratio_from',10,4);
            $table->decimal('color_ratio_to',10,4);
            $table->decimal('no_of_color_from',10,4)->nullable();
            $table->decimal('no_of_color_to',10,4)->nullable();
            $table->decimal('overhead_per_kg',10,4)->nullable();
            $table->decimal('overhead_amount',10,4)->nullable();
            $table->string('remarks',400)->nullable();
            
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
        Schema::dropIfExists('so_aop_mkt_cost_params');
    }
}
