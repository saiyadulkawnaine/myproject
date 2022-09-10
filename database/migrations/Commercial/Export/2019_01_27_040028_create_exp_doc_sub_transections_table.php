<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpDocSubTransectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_doc_sub_transections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_doc_submission_id')->unsigned();
            $table->foreign('exp_doc_submission_id')->references('id')->on('exp_doc_submissions')->onDelete('cascade');      
            $table->unsignedInteger('commercialhead_id')->nullable();
            $table->decimal('doc_value',14,4)->nullable();
            $table->decimal('exch_rate', 12, 4);
            $table->decimal('dom_value',14,4);
            $table->string('ac_loan_no',200)->nullable();
            $table->unsignedInteger('bank_account_id');
            $table->integer('acc_term_loan_id')->unsigned();
            $table->foreign('acc_term_loan_id')->references('id')->on('acc_term_loans');
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
        Schema::dropIfExists('exp_doc_sub_transections');
    }
}
