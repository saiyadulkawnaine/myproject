<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpDocAcceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imp_doc_accepts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('imp_lc_id')->unsigned();
            $table->foreign('imp_lc_id')->references('id')->on('imp_lcs')->onDelete('cascade');
            $table->string('invoice_no',100);
            $table->date('invoice_date');
            $table->date('shipment_date');
            $table->date('company_accep_date');
            $table->date('bank_accep_date')->nullable();
            $table->string('bank_ref',100)->nullable();
            $table->integer('commercial_head_id')->unsigned();
            $table->foreign('commercial_head_id')->references('id')->on('commercial_heads')->onDelete('cascade');
            $table->string('loan_ref',100)->nullable();
            $table->decimal('doc_value',14,4);
            $table->decimal('rate',10,6)->nullable();//interest rate
            $table->string('bl_cargo_no',100)->nullable();
            $table->date('bl_cargo_date')->nullable();
            $table->unsignedSmallInteger('shipment_mode')->nullable();
            $table->unsignedSmallInteger('doc_status')->nullable();
            $table->date('copy_doc_rcv_date')->nullable();
            $table->date('original_doc_rcv_date')->nullable();
            $table->date('doc_to_cf_date')->nullable();
            $table->string('feeder_vessel',100)->nullable();
            $table->string('mother_vessel',100)->nullable();
            $table->date('eta_date')->nullable();
            $table->date('ic_received_date')->nullable();
            $table->string('shipping_bill_no',100)->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->date('discharge_date')->nullable();
            $table->date('port_clearing_date')->nullable();
            $table->string('internal_file_no',100)->nullable();
            $table->string('bill_of_entry_no',100)->nullable();
            $table->string('psi_ref_no',100)->nullable();
            $table->date('maturity_date')->nullable();
            $table->string('container_no',100)->nullable();
            $table->unsignedInteger('qty')->nullable();//packt quantity
            $table->string('remarks',500)->nullable();
            $table->unsignedInteger('bank_account_id');
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
        Schema::dropIfExists('imp_doc_accepts');
    }
}
