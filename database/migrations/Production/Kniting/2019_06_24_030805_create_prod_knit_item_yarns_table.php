<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitItemYarnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knit_item_yarns', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('prod_knit_item_id');
        $table->foreign('prod_knit_item_id')->references('id')->on('prod_knit_items')->onDelete('cascade');

        $table->unsignedInteger('inv_yarn_isu_item_id');
        $table->foreign('inv_yarn_isu_item_id')->references('id')->on('inv_yarn_isu_items')->onDelete('cascade');

        $table->decimal('qty',14,4);
        $table->decimal('rate',14,4);
        $table->decimal('amount',14,4);

        $table->unsignedSmallInteger('created_by')->nulable();
        $table->timestamp('created_at')->nulable();
        $table->unsignedSmallInteger('updated_by')->nulable();
        $table->timestamp('updated_at')->nulable();
        $table->timestamp('deleted_at')->nulable();
        $table->string('created_ip',20)->nulable();
        $table->string('updated_ip',20)->nulable();
        $table->string('deleted_ip',20)->nulable();
        $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_knit_item_yarns');
    }
}
