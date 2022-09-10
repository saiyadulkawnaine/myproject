<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_lcs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local_lc_no',100);
            $table->date('lc_date');
            //$table->unsignedInteger('doc_submitted_to_id');
            $table->decimal('lc_value',14,4);
            $table->unsignedInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('exch_rate', 12, 4);
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->unsignedInteger('production_area_id')->nullable();
            $table->unsignedInteger('beneficiary_id');//bank
            $table->integer('buyer_id')->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->string('buyers_bank',200)->nullable();
            $table->unsignedInteger('exporter_bank_branch_id')->nullable();
            $table->date('lien_date')->nullable();
            $table->date('last_delivery_date');
            $table->unsignedSmallInteger('delivery_mode_id');
            $table->date('lc_expire_date');
            $table->string('delivery_place',100)->nullable();
            $table->string('hs_code',100)->nullable();
            $table->string('customer_lc_sc',300)->nullable();
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
        Schema::dropIfExists('local_exp_lcs');
    }
}
