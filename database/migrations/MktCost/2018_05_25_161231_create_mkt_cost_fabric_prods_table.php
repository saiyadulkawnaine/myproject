<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMktCostFabricProdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mkt_cost_fabric_prods', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('mkt_cost_id')->unsigned();
          $table->foreign('mkt_cost_id')->references('id')->on('mkt_costs')->onDelete('cascade');
          $table->unsignedInteger('mkt_cost_fabric_id')->unsigned();
          $table->foreign('mkt_cost_fabric_id','fabric_id_prod_id')->references('id')->on('mkt_cost_fabrics')->onDelete('cascade');
          $table->unsignedInteger('production_process_id');
		  $table->unsignedTinyInteger('production_process_id')->nullable();
		  $table->unsignedInteger('yarncount_id')->unsigned();
          $table->foreign('yarncount_id')->references('id')->on('yarncounts')->onDelete('cascade');
		  $table->unsignedInteger('colorrange_id')->unsigned();
          $table->foreign('colorrange_id')->references('id')->on('colorranges')->onDelete('cascade');
          $table->decimal('cons', 12, 4);
		  $table->decimal('req_cons', 12, 4);
          $table->decimal('rate', 12, 4);
          $table->decimal('amount', 12, 4);
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
        Schema::dropIfExists('mkt_cost_fabric_prods');
    }
}
