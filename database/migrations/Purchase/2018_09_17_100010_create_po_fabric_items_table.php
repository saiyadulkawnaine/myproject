<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoFabricItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_fabric_items', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_fabric_id');
          $table->foreign('po_fabric_id')->references('id')->on('po_fabrics')->onDelete('cascade');
          $table->unsignedInteger('budget_fabric_id');
          $table->foreign('budget_fabric_id')->references('id')->on('budget_fabrics')->onDelete('cascade');
          $table->decimal('qty',12,4)->nullable();
		      $table->decimal('rate', 12, 4)->nullable();
          $table->decimal('amount', 12, 4)->nullable();
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
        Schema::dropIfExists('po_fabric_items');
    }
}
