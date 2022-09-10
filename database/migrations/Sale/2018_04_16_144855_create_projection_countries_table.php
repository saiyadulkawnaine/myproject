<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectionCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projection_countries', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('projection_id');
          $table->foreign('projection_id')->references('id')->on('projections')->onDelete('cascade');
          $table->unsignedInteger('country_id');
          $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
          $table->unsignedTinyInteger('cut_off')->nullable();
		  $table->date('cut_off_date')->nullable();
          $table->date('country_ship_date');
          $table->string('remarks',255)->nullable();
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
        Schema::dropIfExists('projection_countries');
    }
}
