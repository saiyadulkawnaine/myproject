<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSewingCapacityDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sewing_capacity_dates', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('sewing_capacity_id');
          $table->foreign('sewing_capacity_id')->references('id')->on('sewing_capacities')->onDelete('cascade');
          $table->date('capacity_date');
          $table->unique(['sewing_capacity_id', 'capacity_date'],'sewing_capacity_date');
          $table->string('day_name',15);
          $table->unsignedTinyInteger('day_status');
          $table->unsignedSmallInteger('resource_qty');
          $table->unsignedInteger('mkt_cap_mint');
          $table->unsignedInteger('mkt_cap_pcs');
          $table->unsignedInteger('prod_cap_mint');
          $table->unsignedInteger('prod_cap_pcs');

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
        Schema::dropIfExists('capacity_dates');
    }
}
