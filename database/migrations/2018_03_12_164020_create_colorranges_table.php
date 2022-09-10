<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorrangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colorranges', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',150);
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
        Schema::dropIfExists('colorranges');
    }
}
