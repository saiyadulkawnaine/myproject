<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingFabricRtnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_fabric_rtn_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_dyeing_fabric_rtn_id')->nullable()->unsigned();
            $table->foreign('so_dyeing_fabric_rtn_id')->references('id')->on('so_dyeing_fabric_rtns')->onDelete('cascade');
            $table->integer('so_dyeing_ref_id')->nullable()->unsigned();
            $table->foreign('so_dyeing_ref_id','rtnsodyeingrefid')->references('id')->on('so_dyeing_refs')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->unsignedInteger('no_of_roll')->nullable();
            $table->string('remarks',400)->nullable();                      
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
        Schema::dropIfExists('so_dyeing_fabric_rtn_items');
    }
}
