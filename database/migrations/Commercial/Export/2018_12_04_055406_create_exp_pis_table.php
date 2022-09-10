<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpPisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_pis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pi_no');
            $table->unsignedInteger('sys_pi_no')->nullable();
            $table->unsignedInteger('itemclass_id')->unsigned();
            $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
            $table->unsignedInteger('buyer_id')->nullable()->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('company_id');
          	$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedSmallInteger('pi_validity_days')->nullable();
            $table->date('pi_date');
            $table->string('file_no',100)->nullable();
            $table->unsignedSmallInteger('pay_term_id');
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedSmallInteger('incoterm_id')->nullable();
            $table->string('incoterm_place',100)->nullable();
            $table->date('delivery_date');
            $table->string('port_of_entry',100)->nullable();
            $table->string('port_of_loading',100)->nullable();
            $table->string('port_of_discharge',100)->nullable();
            $table->string('final_destination',100)->nullable();
            $table->string('etd_port',100)->nullable();
            $table->string('eta_port',100)->nullable();
            $table->string('hs_code',100)->nullable();
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
        Schema::dropIfExists('exp_pis');
    }
}
