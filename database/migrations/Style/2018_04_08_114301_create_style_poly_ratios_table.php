<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylePolyRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_poly_ratios', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedInteger('style_poly_id')->unsigned();
          $table->foreign('style_poly_id')->references('id')->on('style_polies')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unsigned();
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedInteger('gmt_id')->nullable();
          $table->unsignedInteger('gmt_ratio');
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
        Schema::dropIfExists('style_poly_ratios');
    }
}
