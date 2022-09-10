<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoGeneralServiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_general_service_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('po_general_service_id');
            $table->foreign('po_general_service_id')->references('id')->on('po_general_services')->onDelete('cascade');
            $table->string('service_description',700);
            $table->decimal('qty',12,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);
            $table->unsignedInteger('demand_by_id');
            $table->unsignedInteger('uom_id');
            $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->unsignedInteger('asset_quantity_cost_id')->nullable();
            $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs')->onDelete('cascade');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('po_general_service_items');
    }
}
