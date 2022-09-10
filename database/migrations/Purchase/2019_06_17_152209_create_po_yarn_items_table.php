<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoYarnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_yarn_items', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_yarn_id');
          $table->foreign('po_yarn_id')->references('id')->on('po_yarns')->onDelete('cascade');
          $table->unsignedInteger('item_account_id');
          $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
          $table->decimal('qty',14,4)->nullable();
		      $table->decimal('rate',14,4)->nullable();
          $table->decimal('amount',14,4)->nullable();
          $table->unsignedSmallInteger('no_of_bag')->nullable();
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
        Schema::dropIfExists('po_yarn_items');
    }
}
