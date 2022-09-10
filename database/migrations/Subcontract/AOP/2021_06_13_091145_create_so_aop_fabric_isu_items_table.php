<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopFabricIsuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_fabric_isu_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('so_aop_fabric_isu_id')->nullable()->unsigned();
            $table->foreign('so_aop_fabric_isu_id')->references('id')->on('so_aop_fabric_isus')->onDelete('cascade');
            $table->integer('so_aop_fabric_rcv_rol_id')->nullable()->unsigned();
            $table->foreign('so_aop_fabric_rcv_rol_id','soaopfabisuid')->references('id')->on('so_aop_fabric_rcv_rols')->onDelete('cascade');
            
            
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
        Schema::dropIfExists('so_aop_fabric_isu_items');
    }
}
