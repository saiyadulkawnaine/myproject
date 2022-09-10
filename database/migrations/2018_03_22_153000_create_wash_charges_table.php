<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWashChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wash_charges', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
		  $table->unsignedInteger('embelishment_id')->unsigned();
          $table->foreign('embelishment_id')->references('id')->on('embelishments')->onDelete('cascade');
          $table->unsignedInteger('embelishment_type_id')->unsigned();
          $table->foreign('embelishment_type_id')->references('id')->on('embelishment_types')->onDelete('cascade');
		  $table->unsignedTinyInteger('embelishment_size_id');
          $table->unsignedInteger('composition_id')->unsigned();
          $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
          $table->unsignedInteger('color_range_id')->unsigned();
          $table->foreign('color_range_id')->references('id')->on('colors')->onDelete('cascade');
          $table->unsignedTinyInteger('fabric_shape_id')->nullable();
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
        Schema::dropIfExists('wash_charges');
    }
}
