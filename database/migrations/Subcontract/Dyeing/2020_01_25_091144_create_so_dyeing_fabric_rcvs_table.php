<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingFabricRcvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_fabric_rcvs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receive_no')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('so_dyeing_id')->nullable()->unsigned();
            $table->foreign('so_dyeing_id')->references('id')->on('so_dyeings')->onDelete('cascade');
            
            $table->date('receive_date')->nullable();
            $table->unsignedTinyInteger('is_self')
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
        Schema::dropIfExists('so_dyeing_fabric_rcvs');
    }
}
