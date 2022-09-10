<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpPreCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_pre_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('cr_date');
            $table->unsignedSmallInteger('loan_type_id');
            $table->string('loan_no',100);
            $table->integer('commercial_head_id')->unsigned();
            $table->foreign('commercial_head_id')->references('id')->on('commercial_heads')->onDelete('cascade');
            $table->integer('acc_term_loan_id')->unsigned();
            $table->foreign('acc_term_loan_id')->references('id')->on('acc_term_loans');
            $table->unsignedInteger('tenor')->nullable();
            $table->decimal('rate',10,6)->nullable();
            $table->date('maturity_date')->nullable();
            $table->decimal('amount',14,4);
            $table->string('purpose',400)->nullable();
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
        Schema::dropIfExists('exp_pre_credits');
    }
}
