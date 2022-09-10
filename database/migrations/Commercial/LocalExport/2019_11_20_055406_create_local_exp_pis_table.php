<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpPisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_pis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('pi_no');
            $table->string('sys_pi_no')->nullable();
            $table->unsignedInteger('production_area_id')->unsigned();
            $table->unsignedInteger('buyer_id')->nullable()->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedSmallInteger('pi_validity_days')->nullable();
            $table->date('pi_date');
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable(); //hidden
            $table->unsignedInteger('currency_id')->nullable();
            $table->string('incoterm_place', 100)->nullable(); //hidden
            $table->date('delivery_date');
            $table->string('delivery_place', 100)->nullable();
            $table->string('hs_code', 100)->nullable();
            $table->string('advise_bank', 200)->nullable();
            $table->string('account_no', 200)->nullable();
            $table->string('swift_code', 200)->nullable();
            $table->string('lc_negotiable', 200)->nullable();
            $table->string('overdue', 200)->nullable();
            $table->string('maturity_date', 200)->nullable();
            $table->string('partial_delivery', 200)->nullable();
            $table->decimal('tolerance', 10, 6)->nullable();
            $table->decimal('exch_rate', 10, 6)->nullable();
            $table->decimal('qty', 12, 6)->nullable();
            $table->decimal('amount', 14, 4)->nullable();
            $table->string('remarks', 500)->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('local_exp_pis');
    }
}
