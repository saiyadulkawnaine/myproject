<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imp_lcs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('supplier_id')->nullable()->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedInteger('lc_to_id');//supplier
            $table->unsignedSmallInteger('lc_type_id');
            //$table->unsignedInteger('issuing_bank_id');//From Bank
            $table->unsignedInteger('issuing_bank_branch_id');//From Bank
            $table->unsignedInteger('bank_account_id');
            $table->date('last_delivery_date');
            $table->date('expiry_date');
            $table->string('expiry_place',150);
            $table->string('lc_no_i',4)->nullable();
            $table->string('lc_no_ii',2)->nullable();
            $table->string('lc_no_iii',2)->nullable();
            $table->string('lc_no_iv',11)->nullable();
            $table->date('lc_application_date')->nullable();
            $table->date('lc_date')->nullable();
            $table->string('lca_no',100)->nullable();
            $table->string('lcaf_no',100)->nullable();
            $table->string('imp_form_no',100)->nullable();
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('exch_rate', 12, 4);
            $table->decimal('amount', 14, 4);
            $table->string('suppliers_bank',500)->nullable();
            $table->string('re_imbursing_bank',200)->nullable();
            $table->unsignedSmallInteger('delivery_mode_id');
            $table->string('port_of_entry',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->string('final_destination',100)->nullable();
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->unsignedSmallInteger('partial_shipment_id')->nullable();//yesno
            $table->unsignedSmallInteger('transhipment_id')->nullable();//yesno
            $table->unsignedSmallInteger('add_conf_ref_id')->nullable();//yesno
            $table->string('add_conf_bank',200)->nullable();
            $table->unsignedSmallInteger('add_conf_charge_id')->nullable();
            $table->string('psi_company',200)->nullable();
            $table->unsignedSmallInteger('maturity_form_id')->nullable();
            $table->string('credit_to_be_advised')->nullable();
            $table->date('etd_port',100)->nullable();
            $table->date('eta_port',100)->nullable();
            $table->string('hs_code',100)->nullable();
            $table->unsignedInteger('clearing_agent_id')->nullable();//supplier
            $table->unsignedSmallInteger('shipping_line_id')->nullable();
            $table->unsignedSmallInteger('insurance_company_id')->nullable();//supplier
            $table->string('cover_note_no',100)->nullable();
            $table->date('cover_note_date')->nullable();
            $table->unsignedSmallInteger('origin_id')->nullable();
            $table->string('ud_no',100)->nullable();
            $table->date('ud_date')->nullable();
            $table->decimal('margin_deposit', 12, 4)->nullable();
            $table->decimal('tolerance', 12, 4)->nullable();
            $table->unsignedInteger('doc_present_days')->nullable();
            $table->unsignedSmallInteger('bonded_warehouse_id')->nullable();//yesno
            $table->integer('gmts_qty')->nullable();
            $table->unsignedSmallInteger('menu_id');
            $table->unsignedSmallInteger('debit_ac_id')->nullable();
            $table->string('commodity',200)->nullable();
            $table->string('advise_bank',500)->nullable();
            $table->unsignedSmallInteger('outside_charge_id')->nullable();
            $table->unsignedSmallInteger('inside_charge_id')->nullable();
            $table->string('other_terms_condition',700)->nullable();
            $table->timestamp('last_untagged_po_at')->nullable();
            $table->timestamp('last_untagged_lc_at')->nullable();
            $table->integer('acc_term_loan_id')->unsigned();
            $table->foreign('acc_term_loan_id')->references('id')->on('acc_term_loans');
            $table->unsignedTinyInteger('ready_to_approve_id');
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedSmallInteger('unapproved_by')->nullable();
            $table->timestamp('unapproved_at')->nullable();
            $table->unsignedSmallInteger('unapproved_count')->nullable();
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
        Schema::dropIfExists('imp_lcs');
    }
}
