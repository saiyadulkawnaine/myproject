<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleFabricationStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_fabrication_stripes', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_fabrication_id')->unsigned();
          $table->foreign('style_fabrication_id')->references('id')->on('style_fabrications')->onDelete('cascade');
		  $table->unsignedInteger('style_color_id')->unsigned();
          $table->foreign('style_color_id')->references('id')->on('style_colors')->onDelete('cascade');
          $table->unsignedInteger('color_id')->unsigned();
          $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
          $table->decimal('measurment', 8, 4);
          $table->unsignedSmallInteger('feeder');
          $table->unsignedTinyInteger('is_dye_wash')->nullable();
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
        Schema::dropIfExists('style_fabrication_stripes');
    }
}
