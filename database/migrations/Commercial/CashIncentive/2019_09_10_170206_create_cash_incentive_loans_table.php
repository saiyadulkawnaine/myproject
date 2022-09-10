<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_ref_id')->unsigned();       
            $table->foreign('cash_incentive_ref_id')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
            $table->date('advance_date');
            $table->string('loan_ref_no',150);
            $table->decimal('rate',10,6)->nullable();
            $table->decimal('advance_per',10,4)->nullable();
            $table->decimal('advance_amount_tk',14,4)->nullable();
            $table->decimal('advance_amount_usd',14,4)->nullable();
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('cash_incentive_loans');
    }
}
