<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveRefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_refs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('incentive_no');
            $table->integer('exp_lc_sc_id')->unsigned();       
            $table->foreign('exp_lc_sc_id')->references('id')->on('exp_lc_scs')->onDelete('cascade');
            $table->unsignedInteger('bank_file_no')->nullable();
            $table->unsignedInteger('region_id');
            $table->date('claim_sub_date');
            $table->unsignedInteger('company_id')->nullable();
            $table->decimal('avg_rate',10,4);
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
        Schema::dropIfExists('cash_incentive_refs');
    }
}
