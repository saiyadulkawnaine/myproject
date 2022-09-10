<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingMktCostQpricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_mkt_cost_qprices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_dyeing_mkt_cost_id');
            $table->foreign('so_dyeing_mkt_cost_id','dyeingmktcostQuotePriceID')->references('id')->on('so_dyeing_mkt_costs')->onDelete('cascade');
            $table->unsignedInteger('qprice_no');
            $table->date('qprice_date');
            $table->string('remarks',400)->nullable();
            $table->unsignedTinyInteger('ready_to_approve_id');

            $table->unsignedSmallInteger('first_approved_by')->nullable();
            $table->timestamp('first_approved_at')->nullable();
            $table->unsignedSmallInteger('second_approved_by')->nullable();
            $table->timestamp('second_approved_at')->nullable();
            $table->unsignedSmallInteger('third_approved_by')->nullable();
            $table->timestamp('third_approved_at')->nullable();
            $table->unsignedSmallInteger('final_approved_by')->nullable();
            $table->timestamp('final_approved_at')->nullable();

            $table->unsignedSmallInteger('returned_by')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->string('returned_coments',2000)->nullable();
            
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
        Schema::dropIfExists('so_dyeing_mkt_cost_qprices');
    }
}
