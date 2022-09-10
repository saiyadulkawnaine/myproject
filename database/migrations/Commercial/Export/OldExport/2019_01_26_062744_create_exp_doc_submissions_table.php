<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpDocSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_doc_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_sales_contract_id')->unsigned();
            $table->foreign('exp_sales_contract_id')->references('id')->on('exp_sales_contracts')->onDelete('cascade');
            $table->date('submission_date');
            $table->unsignedSmallInteger('submission_type_id');
            $table->date('negotiation_date')->nullable();
            $table->string('bank_ref_bill_no',100)->nullable();
            $table->date('bank_ref_date')->nullable();
            $table->unsignedInteger('days_to_realize');
            $table->date('possible_realization_date')->nullable();
            $table->string('courier_recpt_no',100)->nullable();
            $table->date('gsp_courier_date')->nullable();
            $table->string('courier_company',300)->nullable();
            $table->string('bnk_to_bnk_cour_no',100)->nullable();
            $table->date('bnk_to_bnk_cour_date')->nullable();
            $table->string('advice_ref',200)->nullable();
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
        Schema::dropIfExists('exp_doc_submissions');
    }
}
