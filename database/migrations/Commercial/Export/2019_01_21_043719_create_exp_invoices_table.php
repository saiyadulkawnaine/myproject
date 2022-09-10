<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_lc_sc_id')->unsigned();       
            $table->foreign('exp_lc_sc_id')->references('id')->on('exp_lc_scs')->onDelete('cascade');
            $table->unsignedInteger('exp_adv_invoice_id')->nullable();
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->unsignedInteger('invoice_qty');
            $table->decimal('invoice_value',14,4)->nullable();
            $table->string('exp_form_no',100)->nullable();
            $table->date('exp_form_date')->nullable();
            $table->date('actual_ship_date')->nullable();
            $table->integer('country_id')->nullable()->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('remarks',500)->nullable();
            $table->string('file_src',300)->nullable();
            $table->string('category_no',100)->nullable();
            $table->decimal('discount_per',10,6)->nullable();
            $table->decimal('discount_amount',14,4)->nullable();
            $table->decimal('annual_bonus_per',10,6)->nullable();
            $table->decimal('bonus_amount',14,4)->nullable();
            $table->decimal('claim_per',10,6)->nullable();
            $table->decimal('claim_amount',14,4)->nullable();
            $table->decimal('commission',10,6)->nullable();
            $table->decimal('net_inv_value',14,4)->nullable();
            $table->decimal('net_wgt_exp_qty',14,4)->nullable();
            $table->decimal('gross_wgt_exp_qty',14,4)->nullable();
            $table->decimal('cbm',14,4)->nullable();
            $table->string('bl_cargo_no')->nullable();
            $table->date('bl_cargo_date')->nullable();
            $table->date('origin_bl_rev_date')->nullable();
            $table->date('etd_port')->nullable();
            $table->string('feeder_vessel',100)->nullable();
            $table->string('mother_vessel',100)->nullable();
            $table->date('eta_port')->nullable();
            $table->date('ic_recv_date')->nullable();
            $table->string('shipping_mark',400)->nullable();
            $table->unsignedSmallInteger('ship_mode_id')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->string('port_of_entry',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->string('shipping_bill_no',100)->nullable();
            $table->date('shipping_bill_date')->nullable();
            $table->date('ex_factory_date')->nullable();
            $table->string('freight_by_supplier',100)->nullable();
            $table->string('freight_by_buyer',100)->nullable();
            $table->decimal('paid_amount',14,4)->nullable();
            $table->unsignedInteger('total_ctn_qty')->nullable();
            $table->date('advice_date')->nullable();
            $table->decimal('advice_amount',14,4)->nullable();
            $table->unsignedSmallInteger('submit_to_id')->nullable();
            $table->string('rex_declaration',400)->nullable();
            $table->string('discount_remarks',400)->nullable();
            $table->string('bonus_remarks',400)->nullable();
            $table->string('claim_remarks',400)->nullable();
            $table->string('commision_remarks',400)->nullable();
            $table->unsignedInteger('up_charge_amount',14,4);
            $table->string('up_charge_remarks',400)->nullable();
            $table->unsignedInteger('invoice_status_id');

            $table->unsignedInteger('consignee_id');
            $table->unsignedSmallInteger('notifying_party_id')->nullable();

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
        Schema::dropIfExists('exp_invoices');
    }
}
