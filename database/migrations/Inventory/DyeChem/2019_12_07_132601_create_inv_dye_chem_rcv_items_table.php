<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvDyeChemRcvItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dye_chem_rcv_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_dye_chem_rcv_id');
            $table->foreign('inv_dye_chem_rcv_id')->references('id')->on('inv_dye_chem_rcvs')->onDelete('cascade');
            $table->unsignedInteger('po_dye_chem_item_id');
            $table->foreign('po_dye_chem_item_id')->references('id')->on('po_dye_chem_items');
            $table->unsignedInteger('inv_pur_req_item_id');
            $table->foreign('inv_pur_req_item_id')->references('id')->on('inv_pur_req_items');
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->string('batch');
            $table->date('expiry_date');
            $table->unsignedInteger('store_id');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);

            $table->decimal('store_qty',14,4);
            $table->decimal('store_rate',14,4);
            $table->decimal('store_amount',14,4);

            $table->decimal('ile_a',14,4)->nullable();
            $table->decimal('ile_b',14,4)->nullable();
            $table->decimal('ile_c',14,4)->nullable();
            $table->decimal('ile_d',14,4)->nullable();
            $table->decimal('ile_e',14,4)->nullable();
            $table->decimal('ile_f',14,4)->nullable();
            $table->decimal('ile_g',14,4)->nullable();
            

            
            $table->string('room', 100)->nullable();
            $table->string('rack', 100)->nullable();
            $table->string('shelf', 100)->nullable();
            $table->string('remarks', 500)->nullable();

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
        Schema::dropIfExists('inv_dye_chem_rcv_items');
    }
}
