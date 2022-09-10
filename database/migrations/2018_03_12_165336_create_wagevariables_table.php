<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWagevariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wagevariables', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',150)->unique();
          $table->unsignedInteger('production_process_id')->unsigned();
          $table->foreign('production_process_id')->references('id')->on('production_processes')->onDelete('cascade');
          $table->unsignedSmallInteger('sort_id')->nullable();
          $table->unsignedSmallInteger('create_by')->nullable();
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
        Schema::dropIfExists('wagevariables');
    }
}
