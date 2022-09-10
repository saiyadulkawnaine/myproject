<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylePkgRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_pkg_ratios', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->integer('style_pkg_id')->unsigned();
          $table->foreign('style_pkg_id')->references('id')->on('style_pkgs')->onDelete('cascade');
		  $table->unsignedInteger('style_gmt_color_size_id');
		  $table->foreign('style_gmt_color_size_id','style_gm_co_si_sty_pkg_ra_fk')->references('id')->on('style_gmt_color_sizes')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unsigned();
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedInteger('style_color_id')->unsigned();
          $table->foreign('style_color_id')->references('id')->on('style_colors')->onDelete('cascade');
          $table->unsignedInteger('style_size_id')->unsigned();
          $table->foreign('style_size_id')->references('id')->on('style_sizes')->onDelete('cascade');
          $table->unsignedInteger('qty');
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
        Schema::dropIfExists('style_pkg_ratios');
    }
}
