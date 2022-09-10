<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvFinishFabTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_finish_fab_transactions', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('trans_type_id');
            $table->date('trans_date');
            $table->unsignedInteger('inv_finish_fab_rcv_item_id')->nullable();
            $table->foreign('inv_finish_fab_rcv_item_id','invfinishfabrcvitemid')->references('id')->on('inv_finish_fab_rcv_items')->onDelete('cascade');
            $table->unsignedInteger('inv_finish_fab_isu_item_id')->nullable();
            $table->foreign('inv_finish_fab_isu_item_id','invfinishfabisuitemid')->references('id')->on('inv_finish_fab_isu_items')->onDelete('cascade');


            $table->unsignedInteger('inv_finish_fab_rcv_rtn_item_id')->nullable();
            $table->unsignedInteger('inv_finish_fab_isu_rtn_item_id')->nullable();
            $table->unsignedInteger('inv_finish_fab_trn_item_id')->nullable();
            $table->unsignedInteger('inv_finish_fab_item_id');
            $table->foreign('inv_finish_fab_item_id','invfinishfabitemidtrn')->references('id')->on('inv_finish_fab_items');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            
            $table->unsignedInteger('store_id');
            $table->decimal('store_qty',14,4);
            $table->decimal('store_rate',14,4);
            $table->decimal('store_amount',14,4);

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
        Schema::dropIfExists('inv_finish_fab_transactions');
    }
}
