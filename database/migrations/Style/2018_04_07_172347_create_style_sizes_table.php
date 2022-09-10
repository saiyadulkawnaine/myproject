<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_sizes', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unsigned()->nullable();
          //$table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedInteger('size_id')->unsigned();
          $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
          $table->string('size_code',100)->nullable();
          $table->unsignedSmallInteger('sort_id')->nullable();
		  $table->unique(["style_id", "size_id"]);
		  $table->unique(["style_id", "sort_id"]);
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
        Schema::dropIfExists('style_sizes');
    }
}
