<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricprocesslossPercentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabricprocessloss_percents', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('fabricprocessloss_id')->unsigned();
          $table->foreign('fabricprocessloss_id')->references('id')->on('fabricprocesslosses')->onDelete('cascade');
          $table->unsignedTinyInteger('loss_area_id');
          $table->unsignedInteger('process_area_id')->nullable();
          $table->unsignedInteger('loss_percent')->nullable();
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
        Schema::dropIfExists('fabricprocessloss_percents');
    }
}
