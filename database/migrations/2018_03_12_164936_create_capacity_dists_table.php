<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacityDistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_dists', function (Blueprint $table) {
          $table->increments('id')->integer();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->string('location_id',250)->nullable();
          $table->unsignedTinyInteger('prod_type_id');
          $table->unsignedTinyInteger('prod_source_id');
          $table->unsignedSmallInteger('year');
          $table->unsignedInteger('week_id');
          $table->unsignedInteger('mkt_smv');
          $table->unsignedInteger('prod_smv');
          $table->unsignedInteger('mkt_pcs');
          $table->unsignedInteger('prod_pcs');
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
        Schema::dropIfExists('capacity_dists');
    }
}
