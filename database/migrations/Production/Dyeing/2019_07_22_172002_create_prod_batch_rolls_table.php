<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_rolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_batch_id')->unsigned();
            $table->foreign('prod_batch_id')->references('id')->on('prod_batches')->onDelete('cascade');
            $table->integer('so_dyeing_fabric_rcv_rol_id')->unsigned();
            $table->foreign('so_dyeing_fabric_rcv_rol_id')->references('id')->on('so_dyeing_fabric_rcv_rols');
            $table->decimal('qty',14,4)->nullable();
            $table->integer('root_batch_roll_id')->unsigned();
            $table->foreign('root_batch_roll_id')->references('id')->on('prod_batch_rolls');
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
        Schema::dropIfExists('prod_batch_rolls');
    }
}
