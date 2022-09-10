<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_accounts', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('itemcategory_id')->unsigned();
          $table->foreign('itemcategory_id')->references('id')->on('itemcategories')->onDelete('cascade');
          $table->unsignedInteger('itemclass_id')->unsigned();
          $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
          $table->string('sub_class_name',100)->nullable();
          $table->string('sub_class_code',11)->nullable();
          $table->unsignedTinyInteger('item_nature_id')->nullable();
          $table->unsignedInteger('yarncount_id')->unsigned();
          $table->foreign('yarncount_id')->references('id')->on('yarncounts')->onDelete('cascade');
          $table->unsignedInteger('yarntype_id')->unsigned();
          $table->foreign('yarntype_id')->references('id')->on('yarntypes')->onDelete('cascade');
          $table->unsignedInteger('composition_id')->unsigned();
          $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
          $table->string('item_description',150)->nullable();
          $table->string('specification',100)->nullable();
          $table->unsignedInteger('color_id')->unsigned();
          $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
          $table->unsignedInteger('size_id')->unsigned();
          $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
          $table->unsignedTinyInteger('gmt_position')->nullable();
          $table->unsignedSmallInteger('gmt_category')->nullable();
          $table->unsignedInteger('gmtspart_id')->unsigned();
          $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');
          $table->unsignedInteger('autoyarn_id')->unsigned();
          $table->foreign('autoyarn_id')->references('id')->on('autoyarns')->onDelete('cascade');
          $table->unsignedTinyInteger('gsm')->nullable();
          $table->string('dia',10)->nullable();
          $table->string('stitch_length',200)->nullable();
          $table->string('mc_gg',10)->nullable();
          $table->unsignedTinyInteger('fabric_looks')->nullable();
          $table->unsignedSmallInteger('reorder_level')->nullable();
          $table->unsignedSmallInteger('min_level')->nullable();
          $table->unsignedSmallInteger('max_level')->nullable();
          $table->unsignedInteger('uom_id')->unsigned();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
          $table->string('custom_code',100)->nullable();
          $table->unsignedTinyInteger('consumption_level_id')->unsigned();
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
        Schema::dropIfExists('item_accounts');
    }
}
