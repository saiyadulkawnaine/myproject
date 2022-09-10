<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectionQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projection_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('projection_country_id');
          $table->foreign('projection_country_id')->references('id')->on('projection_countries')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id');
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
		  $table->decimal('sam', 8, 4)->nullable();
          $table->unsignedInteger('qty');
          $table->decimal('rate', 8, 4);
          $table->decimal('amount', 8, 4);
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
        Schema::dropIfExists('projection_qties');
    }
}
