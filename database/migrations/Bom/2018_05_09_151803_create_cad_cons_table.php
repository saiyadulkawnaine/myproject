<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cad_cons', function (Blueprint $table) {
        $table->increments('id')->unsignedInteger();
        $table->unsignedInteger('cad_id');
        $table->foreign('cad_id')->references('id')->on('cads')->onDelete('cascade');
        $table->unsignedInteger('style_fabrication_id');
        $table->foreign('style_fabrication_id')->references('id')->on('style_fabrications')->onDelete('cascade');
        $table->unsignedInteger('style_gmt_color_size_id');
        $table->foreign('style_gmt_color_size_id')->references('id')->on('style_gmt_color_sizes')->onDelete('cascade');
        $table->unsignedInteger('style_size_id');
        $table->foreign('style_size_id')->references('id')->on('style_sizes')->onDelete('cascade');
        $table->unsignedInteger('style_color_id');
        $table->foreign('style_color_id')->references('id')->on('style_colors')->onDelete('cascade');
        $table->string('dia',100)->nullable();
        $table->decimal('cons', 12, 4);
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
        Schema::dropIfExists('cad_cons');
    }
}
