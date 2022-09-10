<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtExFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_ex_factories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');   
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedSmallInteger('transport_agent_id')->nullable();
            $table->unsignedSmallInteger('forwarding_agent_id')->nullable();
            $table->date('exfactory_date');
            $table->string('invoice_no',150)->nullable();
            $table->unsignedInteger('exp_invoice_id');
            $table->foreign('exp_invoice_id')->references('id')->on('exp_invoices')->onDelete('cascade');
            $table->string('port_of_loading',100);
            $table->string('driver_name',200)->nullable();
            $table->string('driver_contact_no',150)->nullable();
            $table->string('driver_license_no',200)->nullable();
            $table->string('lock_no',200)->nullable();
            $table->string('recipient',200)->nullable();
            $table->string('remarks',500)->nullable();
            $table->string('truck_no',100)->nullable();
            $table->string('depo_name',200)->nullable();
            $table->unsignedSmallInteger('created_by')->nulable();
            $table->timestamp('created_at')->nulable();
            $table->unsignedSmallInteger('updated_by')->nulable();
            $table->timestamp('updated_at')->nulable();
            $table->timestamp('deleted_at')->nulable();
            $table->string('created_ip',20)->nulable();
            $table->string('updated_ip',20)->nulable();
            $table->string('deleted_ip',20)->nulable();
            $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_gmt_ex_factories');
    }
}
