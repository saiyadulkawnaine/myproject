<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoYarnItemBomQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_yarn_item_bom_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_yarn_item_id');
          $table->foreign('po_yarn_item_id','poyarnitem')->references('id')->on('po_yarn_items')->onDelete('cascade');
          $table->unsignedInteger('budget_yarn_id');
          $table->foreign('budget_yarn_id')->references('id')->on('budget_yarns')->onDelete('cascade');
          $table->unsignedInteger('sale_order_id');
          $table->foreign('sale_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
          $table->decimal('qty',14,4);
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
        Schema::dropIfExists('po_yarn_item_bom_qties');
    }
}
