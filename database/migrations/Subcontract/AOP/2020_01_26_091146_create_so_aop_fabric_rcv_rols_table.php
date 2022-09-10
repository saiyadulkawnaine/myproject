<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopFabricRcvRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_fabric_rcv_rols', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('so_aop_fabric_rcv_item_id')->nullable()->unsigned();
            $table->foreign('so_aop_fabric_rcv_item_id','soaopfabricrcvitemid')->references('id')->on('so_aop_fabric_rcv_items')->onDelete('cascade');
            
            $table->unsignedInteger('prod_finish_dlv_roll_id')->nullable();
            $table->foreign('prod_finish_dlv_roll_id','finishdlvrollidaopfk')->references('id')->on('prod_finish_dlv_rolls');
            $table->decimal('qty',14,4);
            $table->decimal('rate',12,4);
            $table->decimal('amount',14,4);

            $table->string('custom_no', 100)->nullable();
            $table->string('room', 100)->nullable();
            $table->string('rack', 100)->nullable();
            $table->string('shelf', 100)->nullable();
            
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
        Schema::dropIfExists('so_aop_fabric_rcv_rols');
    }
}
