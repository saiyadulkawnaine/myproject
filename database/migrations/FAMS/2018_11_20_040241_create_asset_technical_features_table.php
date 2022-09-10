<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTechnicalFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_technical_features', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_acquisition_id')->unsigned();
            $table->foreign('asset_acquisition_id')->references('id')->on('asset_acquisitions')->onDelete('cascade');
            $table->unsignedInteger('dia_width')->nullable();
            $table->unsignedInteger('gauge')->nullable();
            $table->unsignedInteger('extra_cylinder')->nullable();
            $table->unsignedInteger('no_of_feeder')->nullable();
            $table->string('attachment',300)->nullable();
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
        Schema::dropIfExists('asset_technical_features');
    }
}
