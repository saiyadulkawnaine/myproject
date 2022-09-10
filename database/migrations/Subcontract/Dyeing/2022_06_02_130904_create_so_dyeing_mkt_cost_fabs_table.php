<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingMktCostFabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_mkt_cost_fabs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_dyeing_mkt_cost_id');
            $table->foreign('so_dyeing_mkt_cost_id')->references('id')->on('so_dyeing_mkt_costs')->onDelete('cascade');
            $table->unsignedInteger('autoyarn_id');
            $table->unsignedInteger('colorrange_id');
            $table->unsignedInteger('gsm_weight');
            $table->unsignedInteger('dia');
            $table->unsignedInteger('dyeing_type_id');
            $table->decimal('fabric_wgt',10,4);
            $table->decimal('offer_qty',10,4);
            $table->decimal('color_ratio_from',10,4);
            $table->decimal('color_ratio_to',10,4);
            $table->decimal('liqure_ratio',14,4)->nullable();
            $table->decimal('liqure_wgt',14,4)->nullable();
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
        Schema::dropIfExists('so_dyeing_mkt_cost_fabs');
    }
}
