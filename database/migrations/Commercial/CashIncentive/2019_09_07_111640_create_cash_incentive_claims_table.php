<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_ref_id')->unsigned();       
            $table->foreign('cash_incentive_ref_id')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
            $table->string('bank_bill_no',150);
            $table->unsignedInteger('exp_doc_sub_invoice_id');
            $table->string('invoice_no',100);
            $table->string('exp_form_no',100);
            $table->date('exp_date');
            $table->date('bl_date');
            $table->decimal('invoice_qty',14,4);
            $table->decimal('rate',10,6);
            $table->decimal('invoice_amount',14,4);
            $table->decimal('net_wgt_exp_qty',14,4);
            $table->decimal('knitting_charge_per_kg',14,4);
            $table->decimal('dyeing_charge_per_kg',14,4);
            $table->date('realized_year');
            $table->date('realized_date');
            $table->decimal('realized_amount',14,4);
            $table->decimal('cost_of_export',14,4);
            $table->decimal('cost_of_realization',14,4);
            $table->decimal('freight',14,4)->nullable();
            $table->decimal('net_realized_amount',14,4);
            $table->decimal('claim',10,6);
            $table->decimal('claim_amount',14,4);
            $table->decimal('exch_rate',10,6);
            $table->decimal('local_cur_amount',14,4);
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
        Schema::dropIfExists('cash_incentive_claims');
    }
}
