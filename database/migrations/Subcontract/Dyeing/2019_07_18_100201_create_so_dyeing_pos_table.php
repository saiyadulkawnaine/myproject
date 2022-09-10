<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_pos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_dyeing_id');
            $table->foreign('so_dyeing_id')->references('id')->on('so_dyeings')->onDelete('cascade');
            $table->unsignedInteger('po_dyeing_service_id');
            $table->foreign('po_dyeing_service_id')->references('id')->on('po_dyeing_services');
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
        Schema::dropIfExists('so_dyeing_pos');
    }
}
