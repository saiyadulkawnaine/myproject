<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_lcs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lc_no');
            $table->string('sys_lc_no')->nullable();
            $table->string('file_no',100)->nullable();
            $table->date('lc_date');
            $table->decimal('lc_value')->nullable();
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('exch_rate', 14, 4);//conv_rate
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->unsignedSmallInteger('replacement_lc_id');//combo
            $table->unsignedSmallInteger('exporting_item_id');
            $table->unsignedInteger('beneficiary_id');//combo
            $table->unsignedInteger('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('applicant_id')->nullable();//combo
            $table->string('buyers_bank',200)->nullable();
            $table->unsignedInteger('exporters_bank_id')->nullable();//combo
            $table->date('lien_date')->nullable();
            $table->unsignedInteger('consignee_id')->nullable();//supplier/combo
            $table->unsignedInteger('notifying_party_id')->nullable();//supplier/combo
            $table->string('re_imbursing_bank',200)->nullable();
            $table->unsignedSmallInteger('transferred_lc_id')->nullable();//combo
            $table->string('negotiating_bank',200)->nullable();
            $table->date('last_delivery_date');
            $table->unsignedSmallInteger('delivery_mode_id');
            $table->string('port_of_entry',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->string('final_destination',100)->nullable();
            $table->string('etd_port',100)->nullable();
            $table->string('eta_port',100)->nullable();
            $table->string('hs_code',100)->nullable();
            $table->unsignedInteger('forwarding_agent_id')->nullable();//combo
            $table->unsignedInteger('shipping_line_id')->nullable();//combo
            
            

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
        Schema::dropIfExists('exp_lcs');
    }
}
