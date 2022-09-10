<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleEmbelishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_embelishments', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('style_id');
            $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
            $table->unsignedInteger('style_gmt_id');
            // $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->unsignedInteger('embelishment_id');
            // $table->foreign('embelishment_id')->references('id')->on('embelishments')->onDelete('cascade');
            $table->unsignedInteger('embelishment_type_id');
            //$table->foreign('embelishment_type_id')->references('id')->on('embelishment_types')->onDelete('cascade');
            $table->unsignedTinyInteger('embelishment_size_id');
            $table->unsignedInteger('gmtspart_id');
            $table->unsignedSmallInteger('sort_id');
            $table->unique(["style_id", "style_gmt_id", "embelishment_id", "embelishment_type_id"]);
            $table->unique(["style_gmt_id", "sort_id"]);
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('style_embelishments');
    }
}
