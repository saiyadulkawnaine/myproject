<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleSizeMsureValsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_size_msure_vals', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
		  $table->unsignedInteger('style_size_msure_id');
          $table->foreign('style_size_msure_id')->references('id')->on('style_size_msures')->onDelete('cascade');
          $table->unsignedInteger('style_id')->unsigned();
          $table->unsignedInteger('style_gmt_id')->unsigned();
		  $table->unsignedInteger('style_size_id')->unsigned();
          $table->decimal('msure_value', 12, 4);
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
        Schema::dropIfExists('style_size_msure_vals');
    }
}
