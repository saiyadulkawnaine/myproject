<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveDocPrepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_doc_preps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_ref_id')->unsigned();    
            $table->foreign('cash_incentive_ref_id')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
            $table->unsignedTinyInteger('exp_lc_sc_arranged');
            $table->string('exp_lc_sc_remarks',500)->nullable();
            $table->unsignedTinyInteger('exp_invoice_arranged');
            $table->string('exp_invoice_remarks',500)->nullable();
            $table->unsignedTinyInteger('exp_packinglist_arranged');
            $table->string('exp_packinglist_remarks',500)->nullable();
            $table->unsignedTinyInteger('bill_of_loading_arranged');
            $table->string('bill_of_loading_remarks',500)->nullable();
            $table->unsignedTinyInteger('exp_bill_of_entry_arranged');
            $table->string('exp_bill_of_entry_remarks',500)->nullable();
            $table->unsignedTinyInteger('exp_form_arranged');
            $table->string('exp_form_remarks',500)->nullable();
            $table->unsignedTinyInteger('gsp_co_arranged');
            $table->string('gsp_co_remarks',500)->nullable();
            $table->unsignedTinyInteger('prc_bd_format_arranged');
            $table->string('prc_bd_format_remarks',500)->nullable();
            $table->unsignedTinyInteger('ud_copy_arranged');
            $table->string('ud_copy_remarks',500)->nullable();
            $table->unsignedTinyInteger('btb_lc_arranged');
            $table->string('btb_lc_remarks',500)->nullable();
            $table->unsignedTinyInteger('import_pi_arranged');
            $table->string('import_pi_remarks',500)->nullable();

            $table->unsignedTinyInteger('gsp_certify_btma_arranged');
            $table->string('gsp_certify_btma_remarks',500)->nullable();
            $table->unsignedTinyInteger('vat_eleven_arranged');
            $table->string('vat_eleven_remarks',500)->nullable();
            $table->unsignedTinyInteger('rcv_yarn_challan_arranged');
            $table->string('rcv_yarn_challan_remarks',500)->nullable();
            $table->unsignedTinyInteger('imp_invoice_arranged');
            $table->string('imp_invoice_remarks',500)->nullable();
            $table->unsignedTinyInteger('imp_packing_list_arranged');
            $table->string('imp_packing_list_remarks',500)->nullable();
            $table->unsignedTinyInteger('bnf_certify_spin_mil_arranged');
            $table->string('bnf_certify_spin_mil_remarks',500)->nullable();
            $table->unsignedTinyInteger('certificate_of_origin_arranged');
            $table->string('certificate_of_origin_remarks',500)->nullable();
            $table->unsignedTinyInteger('alt_cash_assist_bgmea_arranged')->nullable();
            $table->string('alt_cash_assist_bgmea_remarks',500)->nullable();
            $table->unsignedTinyInteger('cash_certify_btma_arranged')->nullable();
            $table->string('cash_certify_btma_remarks',500)->nullable();
            
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
        Schema::dropIfExists('cash_incentive_doc_preps');
    }
}
