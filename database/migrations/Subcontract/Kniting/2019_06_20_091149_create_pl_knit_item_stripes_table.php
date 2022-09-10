<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlKnitItemStripesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pl_knit_item_stripes', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('pl_knit_item_id');
            $table->foreign('pl_knit_item_id')->references('id')->on('pl_knit_items')->onDelete('cascade');
            $table->unsignedInteger('style_fabrication_stripe_id')->nullable();
            $table->foreign('style_fabrication_stripe_id')->references('id')->on('style_fabrication_stripes');
            $table->string('measurment');
            $table->unsignedInteger('no_of_feeder');
            $table->unsignedInteger('gmt_color_id');
            $table->unsignedInteger('stripe_color_id');
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('pl_knit_item_stripes');
    }
}
