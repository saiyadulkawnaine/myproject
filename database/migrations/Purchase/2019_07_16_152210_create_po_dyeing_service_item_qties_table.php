<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDyeingServiceItemQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_dyeing_service_item_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_dyeing_service_item_id');
          $table->foreign('po_dyeing_service_item_id')->references('id')->on('po_dyeing_service_items')->onDelete('cascade');

          $table->unsignedInteger('budget_fabric_prod_con_id');
          $table->foreign('budget_fabric_prod_con_id')->references('id')->on('budget_fabric_prod_cons');
          
          $table->unsignedInteger('sales_order_id');
          $table->unsignedInteger('fabric_color_id');
          $table->unsignedInteger('colorrange_id')->nullable();
          $table->foreign('colorrange_id')->references('id')->on('colorranges');

          $table->string('dia',100);
          $table->string('measurment',10);
          $table->string('pl_dia');
          $table->unsignedInteger('pl_gsm_weight');
          $table->string('pl_stitch_length',200);
          $table->string('pl_spandex_stitch_length',200);
          $table->decimal('pl_draft_ratio',12,4);
          $table->unsignedInteger('pl_machine_gg');

          $table->decimal('qty',14,4);
          $table->decimal('pcs_qty',14,4);
          $table->decimal('rate', 14, 4);
          $table->decimal('amount', 14, 4);

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
        Schema::dropIfExists('po_dyeing_service_item_qties');
    }
}
