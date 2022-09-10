<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJhuteSaleDlvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jhute_sale_dlvs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dlv_no');
            $table->unsignedInteger('jhute_sale_dlv_order_id');
            $table->foreign('jhute_sale_dlv_order_id')->references('id')->on('jhute_sale_dlv_orders')->onDelete('cascade');
            $table->date('dlv_date');
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('remarks', 500)->nullable();
            $table->string('driver_name', 200)->nullable();
            $table->string('driver_contact_no', 150)->nullable();
            $table->string('driver_license_no', 200)->nullable();
            $table->string('lock_no', 200)->nullable();
            $table->string('truck_no', 100)->nullable();
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
        Schema::dropIfExists('jhute_sale_dlvs');
    }
}
