<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpLiabilityAdjustChldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imp_liability_adjust_chlds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('imp_liability_adjust_id');
            $table->foreign('imp_liability_adjust_id')->references('id')->on('imp_liability_adjusts')->onDelete('cascade');
            $table->unsignedInteger('payment_head')->nullable();
            $table->unsignedInteger('adj_source')->nullable();  
            $table->decimal('exch_rate', 12, 4)->nullable();
            $table->decimal('amount',14,4);
            $table->decimal('dom_currency',14,4)->nullable();
            $table->unsignedInteger('issuing_bank_id')->nullable();
            $table->unsignedInteger('bank_account_id');
            $table->string('remarks',500)->nullable();
            $table->unsignedSmallInteger('tenor');
            $table->date('maturity_date');
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
        Schema::dropIfExists('imp_liability_adjust_chlds');
    }
}
