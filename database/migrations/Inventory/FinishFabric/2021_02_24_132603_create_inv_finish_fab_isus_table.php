<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvFinishFabIsusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_finish_fab_isus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_isu_id');
            $table->foreign('inv_isu_id')->references('id')->on('inv_isus')->onDelete('cascade');
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
        Schema::dropIfExists('inv_finish_fab_isus');
    }
}
