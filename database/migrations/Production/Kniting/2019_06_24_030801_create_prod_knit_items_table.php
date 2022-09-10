<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knit_items', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('prod_knit_id');
        $table->foreign('prod_knit_id')->references('id')->on('prod_knits')->onDelete('cascade');

        $table->unsignedInteger('pl_knit_item_id')->nulable();
        $table->foreign('pl_knit_item_id')->references('id')->on('pl_knit_items');
        $table->unsignedInteger('asset_quantity_cost_id')->nulable();
        $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs');
        $table->unsignedInteger('operator_id');
        $table->string('machine_info_outside',300)->nulable();

        $table->unsignedInteger('po_knit_service_item_qty_id')->nulable();
        $table->foreign('po_knit_service_item_qty_id')->references('id')->on('po_knit_service_item_qties');

        $table->unsignedInteger('gsm_weight');
        $table->string('dia');
        $table->string('stitch_length',200);
        $table->string('spandex_stitch_length',200);
        $table->string('measurment');
        $table->decimal('draft_ratio',12,4);
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
        Schema::dropIfExists('prod_knit_items');
    }
}
