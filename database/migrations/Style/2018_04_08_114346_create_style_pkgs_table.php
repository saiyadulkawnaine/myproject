<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylePkgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_pkgs', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedInteger('itemclass_id')->unsigned();
          $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
          $table->string('assortment_name',200);
          $table->string('spec',100);
          $table->unsignedInteger('qty')->nullable();
          $table->unsignedInteger('packing_type')->nullable();
          $table->unsignedTinyInteger('assortment')->nullable();
          $table->unsignedSmallInteger('created_by')->nullable();
          $table->timestamp('created_at')->nullable();
          $table->unsignedSmallInteger('updated_by')->nullable();
          $table->timestamp('updated_at')->nullable();
          $table->timestamp('deleted_at')->nullable();
          $table->string('created_ip',20)->nullable();
          $table->string('updated_ip',20)->nullable();
          $table->string('deleted_ip',20)->nullable();
          $table->unsignedTinyInteger('row_status')->nullable()->default(1);
          $table->unsignedTinyInteger(' is_created_by_system
          ')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('style_pkgs');
    }
}
