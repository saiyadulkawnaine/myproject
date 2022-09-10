<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitItemPlItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knit_item_pl_items', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('prod_knit_item_id');
        $table->foreign('prod_knit_item_id')->references('id')->on('prod_knit_items')->onDelete('cascade');

        $table->unsignedInteger('pl_knit_item_id');
        $table->foreign('pl_knit_item_id')->references('id')->on('pl_knit_items')->onDelete('cascade');
        $table->unsignedInteger('asset_quantity_cost_id');
        $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs');
        $table->unsignedInteger('operator_id');
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
        Schema::dropIfExists('prod_knit_item_pl_items');
    }
}
