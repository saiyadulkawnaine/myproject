<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitItemPoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knit_item_po_items', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('prod_knit_item_id');
        $table->foreign('prod_knit_item_id')->references('id')->on('prod_knit_items')->onDelete('cascade');
        $table->unsignedInteger('po_knit_service_item_qty_id');
        $table->foreign('po_knit_service_item_qty_id')->references('id')->on('po_knit_service_item_qties')->onDelete('cascade');
        $table->unsignedSmallInteger('created_by')->nulable();
        $table->timestamp('created_at')->nulable();
        $table->unsignedSmallInteger('updated_by')->nulable();
        $table->timestamp('updated_at')->nulable();
        $table->timestamp('deleted_at')->nulable();
        $table->string('created_ip',20)->nulable();
        $table->string('updated_ip',20)->nulable();
        $table->string('deleted_ip',20)->nulable();
        $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_knit_item_po_items');
    }
}
