<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmtsProcessLossPersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmts_process_loss_pers', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('gmts_process_loss_id')->unsigned();
          $table->foreign('gmts_process_loss_id')->references('id')->on('gmts_process_losses')->onDelete('cascade');
          $table->unsignedInteger('production_process_id')->unsigned();
          $table->foreign('production_process_id')->references('id')->on('production_processes')->onDelete('cascade');
          $table->unsignedInteger('embelishment_type_id')->unsigned()->nullable();
          $table->decimal('process_loss_per', 8, 4);
          $table->unsignedSmallInteger('created_by')->nullable();
          $table->timestamp('created_at')->nullable();
          $table->unsignedSmallInteger('updated_by')->nullable();
          $table->timestamp('updated_at')->nullable();
          $table->timestamp('deleted_at')->nullable();
          $table->string('created_ip',20)->nullable();
          $table->string('updated_ip',20)->nullable();
          $table->string('deleted_ip',20)->nullable();
          $table->unsignedTinyInteger('row_status')->nullable()->default(1);
		  $table->unique(['gmts_process_loss_id', 'production_process_id', 'embelishment_type_id'],'production_process_loss_per_u');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gmts_process_loss_pers');
    }
}
