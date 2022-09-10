<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdAopBatchRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_aop_batch_rolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_aop_batch_id')->unsigned();
            $table->foreign('prod_aop_batch_id')->references('id')->on('prod_aop_batches')->onDelete('cascade');
            $table->integer('so_aop_fabric_isu_item_id')->unsigned();
            $table->foreign('so_aop_fabric_isu_item_id')->references('id')->on('so_aop_fabric_isu_items');
            $table->decimal('qty',14,4)->nullable();
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
        Schema::dropIfExists('prod_aop_batch_rolls');
    }
}
