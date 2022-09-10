<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccTransPrntsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_trans_prnts', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('acc_year_id');
            $table->foreign('acc_year_id')->references('id')->on('acc_years')->onDelete('cascade');
            $table->unsignedInteger('acc_period_id');
            $table->foreign('acc_period_id')->references('id')->on('acc_periods')->onDelete('cascade');
            $table->date('trans_date');
            $table->unsignedInteger('page_id')->nullable();
            $table->unsignedSmallInteger('trans_type_id');
            $table->unsignedInteger('trans_no');
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('instrument_no',20)->nullable();
            $table->string('pay_to',100)->nullable();
            $table->date('place_date')->nullable();
            $table->decimal('amount', 12, 4)->nullable();
            $table->decimal('amount_foreign', 12, 4)->nullable();
            $table->unsignedTinyInteger('is_reversed')->nullable()->default(0);
            $table->unsignedTinyInteger('is_locked')->nullable()->default(0);
            $table->string('narration',500)->nullable();
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
        Schema::dropIfExists('acc_trans_prnts');
    }
}
