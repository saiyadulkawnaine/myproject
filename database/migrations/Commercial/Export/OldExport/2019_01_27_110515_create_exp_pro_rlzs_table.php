<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpProRlzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_pro_rlzs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_sales_contract_id')->unsigned();
            $table->foreign('exp_sales_contract_id')->references('id')->on('exp_sales_contracts')->onDelete('cascade');
            $table->string('realized_fdbc_no')->nullable();
            $table->unsignedInteger('beneficiary_id');
            $table->integer('buyer_id')->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('fdbc_no');
            $table->date('realization_date');
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
        Schema::dropIfExists('exp_pro_rlzs');
    }
}
