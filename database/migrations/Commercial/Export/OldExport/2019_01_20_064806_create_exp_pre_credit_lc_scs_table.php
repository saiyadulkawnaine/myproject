<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpPreCreditLcScsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_pre_credit_lc_scs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exp_pre_credit_id')->unsigned();
            $table->foreign('exp_pre_credit_id')->references('id')->on('exp_pre_credits')->onDelete('cascade');
            $table->integer('exp_sales_contract_id')->unsigned();
            $table->foreign('exp_sales_contract_id')->references('id')->on('exp_sales_contracts')->onDelete('cascade');
            $table->unsignedInteger('credit_taken');
            $table->decimal('exch_rate', 12, 4);
            $table->unsignedInteger('equivalent_fc')->nullable();
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
        Schema::dropIfExists('exp_pre_credit_lc_scs');
    }
}
