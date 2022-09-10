<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubInbOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_inb_order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sub_inb_order_id');
            $table->foreign('sub_inb_order_id')->references('id')->on('sub_inb_orders')->onDelete('cascade');
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
            $table->unsignedInteger('gsm')->nullable();
            $table->unsignedInteger('dia')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('smv',12,4)->nullable();
            $table->unsignedInteger('uom_id');
            $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
            $table->unsignedInteger('qty');
            $table->decimal('rate',12,4);
            $table->decimal('amount',14,4);
            $table->date('delivery_date');
            $table->string('delivery_point')->nullable();
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
        Schema::dropIfExists('sub_inb_order_products');
    }
}
