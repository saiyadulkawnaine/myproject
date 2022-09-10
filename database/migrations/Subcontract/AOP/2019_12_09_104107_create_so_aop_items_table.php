<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_aop_id');
            $table->foreign('so_aop_id')->references('id')->on('so_aops')->onDelete('cascade');
            $table->unsignedInteger('so_aop_ref_id');
            $table->foreign('so_aop_ref_id')->references('id')->on('so_aop_refs')->onDelete('cascade');
            $table->unsignedInteger('gmtspart_id')->unsigned();
            $table->foreign('gmtspart_id')->references('id')->on('gmtsparts');
            $table->unsignedInteger('autoyarn_id');
            $table->foreign('autoyarn_id')->references('id')->on('autoyarns');
            $table->unsignedTinyInteger('fabric_look_id');
            $table->unsignedInteger('fabric_color_id');
            $table->unsignedInteger('colorrange_id')->nullable();
            //$table->unsignedInteger('dyeing_type_id')->nullable();
            $table->foreign('colorrange_id')->references('id')->on('colorranges');
            $table->unsignedInteger('fabric_shape_id');
            $table->unsignedInteger('gsm_weight');
            $table->unsignedInteger('uom_id');
            $table->decimal('qty',14,4);
            $table->decimal('rate',12,4);
            $table->decimal('amount',14,4);
            $table->date('delivery_date');
            $table->string('delivery_point')->nullable();
            $table->unsignedInteger('gmt_buyer')->nullable();
            $table->string('gmt_style_ref',100)->nullable();
            $table->string('gmt_sale_order_no',100)->nullable();
            $table->unsignedInteger('embelishment_type_id')->nullable();
            $table->unsignedInteger('coverage')->nullable();
            $table->unsignedInteger('impression')->nullable();
            $table->unsignedTinyInteger('bill_for')->nullable();
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
        Schema::dropIfExists('so_aop_items');
    }
}
