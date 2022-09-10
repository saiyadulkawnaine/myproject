<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoyarnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autoyarns', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('fabric_nature_Id');
          $table->string('fabric_type',200);
          $table->unsignedInteger('itemclass_id')->unsigned();
          $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
          $table->unsignedInteger('construction_id')->unsigned();
          $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
          $table->unsignedTinyInteger('gsm')->nullable();
          $table->unsignedTinyInteger('machine_dia')->nullable();
          $table->unsignedTinyInteger('fabric_dia')->nullable();
          $table->unsignedTinyInteger('machine_gg')->nullable();
          $table->string('stitch_length', 200)->nullable();
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
        Schema::dropIfExists('autoyarns');
    }
}
