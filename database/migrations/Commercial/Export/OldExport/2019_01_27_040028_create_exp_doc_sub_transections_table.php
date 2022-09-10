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
            $table->unsignedInteger('dom_currency_id')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->string('ac_loan_no',200)->nullable();
            $table->decimal('exch_rate',12,6)->nullable();
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
