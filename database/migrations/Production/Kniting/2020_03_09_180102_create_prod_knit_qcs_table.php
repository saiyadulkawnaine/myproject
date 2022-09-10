<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitQcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knit_qcs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('prod_knit_rcv_by_qc_id')->unique();
            $table->foreign('prod_knit_rcv_by_qc_id')->references('id')->on('prod_knit_rcv_by_qcs')->onDelete('cascade');

            $table->unsignedInteger('prod_knit_item_roll_id')->unique();
            $table->foreign('prod_knit_item_roll_id')->references('id')->on('prod_knit_item_rolls')->onDelete('cascade');

            $table->date('qc_date');
            $table->decimal('gsm_weight',14,4);
            $table->string('dia_width',100);
            $table->string('measurement',50);
            $table->decimal('roll_length',14,4);
            $table->decimal('shrink_per',14,4);
            $table->decimal('reject_qty',14,4);
            $table->decimal('qc_pass_qty',14,4);
            $table->decimal('reject_qty_pcs',14,4);
            $table->decimal('qc_pass_qty_pcs',14,4);
            $table->unsignedSmallInteger('qc_result');
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
        Schema::dropIfExists('prod_knit_qcs');
    }
}
