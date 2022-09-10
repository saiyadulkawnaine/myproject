<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvPurReqItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_pur_req_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_pur_req_id')->unsigned();
            $table->foreign('inv_pur_req_id')->references('id')->on('inv_pur_reqs')->onDelete('cascade');
            $table->unsignedInteger('item_account_id')->unsigned();
            $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
            $table->integer('department_id')->nullable()->unsigned();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();            
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
        Schema::dropIfExists('inv_pur_req_items');
    }
}
