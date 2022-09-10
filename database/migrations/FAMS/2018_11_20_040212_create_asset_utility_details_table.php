<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetUtilityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_utility_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_acquisition_id')->unsigned();
            $table->foreign('asset_acquisition_id')->references('id')->on('asset_acquisitions')->onDelete('cascade');
            $table->string('power_consumption')->nullable();   
            $table->string('water_consumption')->nullable();
            $table->string('air_consumption')->nullable();
            $table->string('steam_consumption')->nullable();
            $table->string('gas_consumption')->nullable();
            $table->string('power_stating_load')->nullable();
            $table->string('power_running_load')->nullable();
            $table->decimal('power_rate',12,4)->nullable();
            $table->decimal('water_rate',12,4)->nullable();
            $table->decimal('air_rate',12,4)->nullable();
            $table->decimal('steam_rate',12,4)->nullable();
            $table->decimal('gas_rate',12,4)->nullable();
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
        Schema::dropIfExists('asset_utility_details');
    }
}
