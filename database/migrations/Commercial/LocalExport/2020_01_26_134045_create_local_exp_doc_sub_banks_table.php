<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpDocSubBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_doc_sub_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_exp_doc_sub_accept_id')->unsigned();
            $table->foreign('local_exp_doc_sub_accept_id','localdocsubaccept')->references('id')->on('local_exp_doc_sub_accepts')->onDelete('cascade');
            $table->date('submission_date');
            $table->unsignedSmallInteger('submission_type_id');
            $table->date('negotiation_date')->nullable();
            $table->string('bank_ref_bill_no',100)->nullable();
            $table->date('bank_ref_date')->nullable();
            $table->decimal('doc_value')->nullable();
            $table->string('courier_recpt_no',100)->nullable();
            $table->string('courier_company',300)->nullable();
            $table->string('bnk_to_bnk_cour_no',100)->nullable();
            $table->date('bnk_to_bnk_cour_date')->nullable();
            $table->date('maturity_rcv_date')->nullable();
            $table->date('place_for_purchase')->nullable();
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
        Schema::dropIfExists('local_exp_doc_sub_banks');
    }
}
