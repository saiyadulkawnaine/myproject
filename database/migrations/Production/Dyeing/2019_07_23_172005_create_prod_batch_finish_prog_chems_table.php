<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchFinishProgChemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_finish_prog_chems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_batch_finish_prog_id')->unsigned();
            $table->foreign('prod_batch_finish_prog_id','prod_123')->references('id')->on('prod_batch_finish_progs');
            $table->integer('item_account_id')->unsigned();
            $table->foreign('item_account_id','item_account_id123')->references('id')->on('item_accounts');
            $table->decimal('qty',14,4)->nullable();
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
        Schema::dropIfExists('prod_batch_finish_prog_chems');
    }
}
