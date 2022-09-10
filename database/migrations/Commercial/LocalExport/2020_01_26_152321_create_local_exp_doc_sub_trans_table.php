<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpDocSubTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_doc_sub_trans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_exp_doc_sub_bank_id')->unsigned();
            $table->foreign('local_exp_doc_sub_bank_id')->references('id')->on('local_exp_doc_sub_banks')->onDelete('cascade');      
            $table->unsignedInteger('commercialhead_id')->nullable();
            $table->decimal('doc_value',14,4)->nullable();
            $table->decimal('exch_rate', 12, 6);
            $table->decimal('dom_value',14,4);
            $table->string('ac_loan_no',200)->nullable();
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
        Schema::dropIfExists('local_exp_doc_sub_trans');
    }
}
