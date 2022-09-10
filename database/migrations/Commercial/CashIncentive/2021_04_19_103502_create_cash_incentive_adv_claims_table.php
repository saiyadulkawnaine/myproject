<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveAdvClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_adv_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_incentive_adv_id');
            $table->foreign('cash_incentive_adv_id')->references('id')->on('cash_incentive_advs')->onDelete('cascade');
            $table->unsignedInteger('cash_incentive_ref_id');       
            $table->foreign('cash_incentive_ref_id','refId')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
            $table->decimal('rate',10,6)->nullable();
            $table->decimal('amount',14,4)->nullable();
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
        Schema::dropIfExists('cash_incentive_adv_claims');
    }
}
