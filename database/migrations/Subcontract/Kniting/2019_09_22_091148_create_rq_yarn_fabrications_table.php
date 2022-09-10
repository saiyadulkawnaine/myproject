<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRqYarnFabricationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rq_yarn_fabrications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rq_yarn_id');
            $table->foreign('rq_yarn_id')->references('id')->on('rq_yarns')->onDelete('cascade');
            
            $table->unsignedInteger('pl_knit_item_id')->nulable();
            $table->foreign('pl_knit_item_id')->references('id')->on('pl_knit_items')->onDelete('cascade');
           $table->unsignedInteger('po_knit_service_item_qty_id')->nulable();
           $table->foreign('po_knit_service_item_qty_id')->references('id')->on('po_knit_service_item_qties');

            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('rq_yarn_fabrications');
    }
}
