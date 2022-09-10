<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvDyeChemIsuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dye_chem_isu_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_isu_id');
            $table->foreign('inv_isu_id')->references('id')->on('inv_isus')->onDelete('cascade');
            $table->unsignedInteger('inv_dye_chem_isu_rq_item_id');
            $table->foreign('inv_dye_chem_isu_rq_item_id')->references('id')->on('inv_dye_chem_isu_rq_items')->onDelete('cascade');
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->unsignedInteger('store_id');
            $table->string('batch');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);
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
        Schema::dropIfExists('inv_dye_chem_isu_items');
    }
}
