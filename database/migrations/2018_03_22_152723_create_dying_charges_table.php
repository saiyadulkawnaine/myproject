<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDyingChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dying_charges', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
		  $table->unsignedInteger('construction_id')->unsigned();
          $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
          $table->unsignedInteger('composition_id')->unsigned();
          $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
		  $table->unsignedInteger('autoyarn_id')->unsigned();
          $table->foreign('autoyarn_id')->references('id')->on('autoyarns')->onDelete('cascade');
          $table->unsignedInteger('color_range_id')->unsigned();
          $table->foreign('color_range_id')->references('id')->on('colors')->onDelete('cascade');
          $table->unsignedTinyInteger('fabric_shape_id')->nullable();
          $table->unsignedTinyInteger('process_for_id')->nullable();
          $table->unsignedInteger('production_process_id')->unsigned();
          $table->foreign('production_process_id')->references('id')->on('production_processes')->onDelete('cascade');
          $table->decimal('rate', 8, 4);
          $table->unsignedInteger('uom_id')->unsigned();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
          $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('dying_charges');
    }
}
