<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGmtspartMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmtspart_menus', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('gmtspart_id')->nullable();
          $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');
          $table->unsignedInteger('menu_id')->nullable();
          $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
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
        Schema::dropIfExists('gmtspart_menus');
    }
}
