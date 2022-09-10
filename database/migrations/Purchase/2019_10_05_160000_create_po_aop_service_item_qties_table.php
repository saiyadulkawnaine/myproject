<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoAopServiceItemQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_aop_service_item_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_aop_service_item_id');
          $table->foreign('po_aop_service_item_id')->references('id')->on('po_aop_service_items')->onDelete('cascade');
          $table->unsignedInteger('budget_fabric_prod_con_id');
          $table->foreign('budget_fabric_prod_con_id')->references('id')->on('budget_fabric_prod_cons');
          $table->unsignedInteger('colorrange_id')->nullable();
          $table->foreign('colorrange_id')->references('id')->on('colorranges');
          $table->decimal('qty',14,4);
          $table->decimal('pcs_qty',14,4);
          $table->decimal('rate', 14, 4);
          $table->decimal('amount', 14, 4);
          $table->unsignedInteger('embelishment_type_id')->nullable();
          $table->unsignedInteger('coverage')->nullable();
          $table->unsignedInteger('impression')->nullable();
          $table->string('dia',100);
          $table->string('measurment',100);
          $table->unsignedInteger('sales_order_id')->nullable();
          $table->unsignedInteger('fabric_color_id')->nullable();
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
        Schema::dropIfExists('po_aop_service_item_qties');
    }
}
