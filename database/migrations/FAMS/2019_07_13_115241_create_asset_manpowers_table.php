<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetManpowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_manpowers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_acquisition_id');
            $table->foreign('asset_acquisition_id')->references('id')->on('asset_acquisitions')->onDelete('cascade');

            $table->Integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id')->references('id')->on('employee_h_rs')->onDelete('cascade');

            $table->date('tenure_start');
            $table->date('tenure_end');
           // $table->unsignedInteger('machine_id')->nullable();
           
           $table->unsignedInteger('asset_quantity_cost_id');
           $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs')->onDelete('cascade');
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
        Schema::dropIfExists('asset_manpowers');
    }
}
