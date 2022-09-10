<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleFabricationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_fabrications', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unsigned();
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedTinyInteger('fabric_nature_id');
          $table->unsignedInteger('gmtspart_id')->unsigned();
          $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');
          $table->unsignedInteger('autoyarn_id')->unsigned();
          $table->foreign('autoyarn_id')->references('id')->on('autoyarns')->onDelete('cascade');
          $table->unsignedTinyInteger('fabric_look_id');
          $table->unsignedTinyInteger('material_source_id');
          $table->unsignedInteger('yarncount_id')->unsigned();
          $table->foreign('yarncount_id')->references('id')->on('yarncounts')->onDelete('cascade');
          $table->unsignedTinyInteger('is_stripe');
          $table->unsignedInteger('image_src')->nullable();
          $table->unsignedInteger('fabric_shape_id');
          $table->unsignedInteger('uom_id')->unsigned();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
		  $table->unsignedTinyInteger('embelishment_type_id');
		  $table->foreign('embelishment_type_id')->references('id')->on('embelishment_types')->onDelete('cascade');
          $table->unsignedTinyInteger('coverage');
          $table->unsignedTinyInteger('impression');
		  $table->unsignedTinyInteger('is_narrow');
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
        Schema::dropIfExists('style_fabrications');
    }
}
