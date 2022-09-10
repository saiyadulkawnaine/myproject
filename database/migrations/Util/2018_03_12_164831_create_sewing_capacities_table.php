<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSewingCapacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewing_capacities', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->unsignedInteger('location_id')->nullable();
          $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
          $table->unsignedTinyInteger('prod_source_id');
          $table->unsignedSmallInteger('year');
          $table->unique(['company_id', 'location_id','prod_source_id','year']);
          $table->decimal('mkt_eff_percent', 12, 4);
          $table->decimal('prod_eff_percent', 12, 4);
          $table->decimal('basic_smv',12,4);
          $table->unsignedTinyInteger('working_hour');
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
        Schema::dropIfExists('sewing_capacities');
    }
}
