<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDyeChemItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_dye_chem_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('po_dye_chem_id');
            $table->foreign('po_dye_chem_id')->references('id')->on('po_dye_chems')->onDelete('cascade');
            $table->unsignedInteger('inv_pur_req_item_id');
            $table->foreign('inv_pur_req_item_id')->references('id')->on('inv_pur_req_items');
            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
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
        Schema::dropIfExists('po_dye_chem_items');
    }
}
