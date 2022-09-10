<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleSampleCsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_sample_cs', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_sample_id');
          $table->foreign('style_sample_id')->references('id')->on('style_samples')->onDelete('cascade');
		  $table->unsignedInteger('style_gmt_color_size_id');
		  $table->foreign('style_gmt_color_size_id','style_gm_co_si_sty_sam_cs_fk')->references('id')->on('style_gmt_color_sizes')->onDelete('cascade');
          $table->unsignedInteger('style_color_id')->unsigned();
          $table->foreign('style_color_id')->references('id')->on('style_colors')->onDelete('cascade');
          $table->unsignedInteger('style_size_id')->unsigned();
          $table->foreign('style_size_id')->references('id')->on('style_sizes')->onDelete('cascade');
          $table->unsignedInteger('qty')->nullable();
		  $table->decimal('rate',12,4)->nullable();
		  $table->decimal('amount',12,4)->nullable();
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
        Schema::dropIfExists('style_sample_cs');
    }
}
