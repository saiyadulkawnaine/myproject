<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoKnitDlvItemYarnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_knit_dlv_item_yarns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_knit_dlv_item_id')->nullable()->unsigned();
            $table->foreign('so_knit_dlv_item_id','soknitdlvitemid')->references('id')->on('so_knit_dlv_items')->onDelete('cascade');
            $table->integer('so_knit_yarn_rcv_item_id')->nullable()->unsigned();
            $table->foreign('so_knit_yarn_rcv_item_id','soknityarnrcvitemid')->references('id')->on('so_knit_yarn_rcv_items')->onDelete('cascade');
            $table->decimal('qty',14,4);
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
        Schema::dropIfExists('so_knit_dlv_item_yarns');
    }
}
