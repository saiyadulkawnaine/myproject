<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmtsProcessLossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmts_process_losses', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->unsignedInteger('buyer_id')->unsigned();
          $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
          $table->unsignedInteger('gmt_qty_range_start');
          $table->unsignedInteger('gmt_qty_range_end');
          $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('gmts_process_losses');
    }
}
