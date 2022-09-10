<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvPurReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_pur_reqs', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedTinyInteger('requisition_type_id');
            $table->unsignedInteger('requisition_no');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->date('req_date');
            $table->date('delivery_by')->nullable();//purchase
            $table->date('disburse_by')->nullable();//cash
            $table->unsignedInteger('demand_by_id');
            $table->unsignedInteger('price_verified_by_id')->nullable();
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedSmallInteger('pay_mode')->nullable();
            $table->string('remarks',500)->nullable();
            
            $table->unsignedSmallInteger('first_approved_by')->nullable();
            $table->timestamp('first_approved_at')->nullable();
            $table->unsignedSmallInteger('second_approved_by')->nullable();
            $table->timestamp('second_approved_at')->nullable();
            $table->unsignedSmallInteger('third_approved_by')->nullable();
            $table->timestamp('third_approved_at')->nullable();
            $table->unsignedSmallInteger('final_approved_by')->nullable();
            $table->timestamp('final_approved_at')->nullable();

            $table->unsignedInteger('job_done_id')->nullable();
            $table->date('job_completion_date')->nullable();

            $table->unsignedSmallInteger('returned_by')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->string('returned_coments',2000)->nullable();

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
        Schema::dropIfExists('inv_pur_reqs');
    }
}
