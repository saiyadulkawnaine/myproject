<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//ExpSalesContract
class CreateExpLcScsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_lc_scs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('file_no')->nullable();
            $table->string('lc_sc_no',100);
            $table->unsignedTinyInteger('sc_or_lc');
            $table->date('lc_sc_date');
            $table->unsignedInteger('doc_submitted_to_id');
            $table->unsignedSmallInteger('lc_sc_nature_id');
            $table->decimal('lc_sc_value',14,4);
            $table->unsignedInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('exch_rate', 12, 4);
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->unsignedSmallInteger('exporting_item_id');
            $table->unsignedInteger('beneficiary_id');//bank
            $table->integer('buyer_id')->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->string('buyers_bank',200)->nullable();
            //$table->unsignedSmallInteger('exporters_bank_id')->nullable();//combo
            $table->unsignedInteger('exporter_bank_branch_id')->nullable();
            $table->date('lien_date')->nullable();
            $table->string('re_imbursing_bank')->nullable();
            $table->unsignedInteger('consignee_id');//supplier
            $table->unsignedSmallInteger('notifying_party_id')->nullable();//supplier
            $table->date('last_delivery_date');
            $table->unsignedSmallInteger('delivery_mode_id');
            $table->string('port_of_entry',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->string('final_destination',100)->nullable();
            $table->date('etd_port',100)->nullable();
            $table->date('eta_port',100)->nullable();
            $table->string('hs_code',100)->nullable();
            $table->unsignedSmallInteger('forwarding_agent_id')->nullable();//combo
            $table->unsignedSmallInteger('shipping_line_id')->nullable();//combo
            $table->string('remarks',500)->nullable();
            $table->decimal('local_commission_per',10,6)->nullable();
            $table->decimal('foreign_commission_per',10,6)->nullable();
            $table->string('transfer_bank',400)->nullable();
            $table->string('advise_bank',400)->nullable();
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
        Schema::dropIfExists('exp_lc_scs');
    }
}
