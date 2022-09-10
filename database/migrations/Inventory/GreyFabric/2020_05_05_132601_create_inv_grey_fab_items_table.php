<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvGreyFabItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_grey_fab_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();

            $table->unsignedInteger('autoyarn_id')->unsigned();
            $table->foreign('autoyarn_id')->references('id')->on('autoyarns');
            $table->unsignedInteger('gmtspart_id')->unsigned();
            $table->foreign('gmtspart_id')->references('id')->on('gmtsparts');
            $table->unsignedTinyInteger('fabric_look_id');
            $table->unsignedInteger('fabric_shape_id');
            $table->unsignedInteger('gsm_weight')->nullable();
            $table->string('dia',100);
            $table->string('measurment',10);
            $table->decimal('roll_length',14,4);
            $table->string('stitch_length',200);
            $table->decimal('shrink_per',14,4);
            $table->unsignedInteger('colorrange_id')->nullable();
            $table->foreign('colorrange_id')->references('id')->on('colorranges');
            
            $table->unique(['autoyarn_id', 'gmtspart_id','fabric_look_id','fabric_shape_id','gsm_weight','dia','measurment','roll_length','stitch_length','shrink_per','colorrange_id'],'grey_fab_item_uq');

            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->increments('parent_id')->unsignedInteger()->nullable()->default(0);
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
        Schema::dropIfExists('inv_grey_fab_items');
    }
}
