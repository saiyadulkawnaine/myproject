<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMktCostFabricConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mkt_cost_fabric_cons', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('mkt_cost_fabric_id')->unsigned();
          $table->foreign('mkt_cost_fabric_id')->references('id')->on('mkt_cost_fabrics')->onDelete('cascade');
          $table->string('style_color_id',10)->nullable();
          $table->string('style_size_id',10)->nullable();
          $table->string('dia',100)->nullable();
          $table->decimal('cons', 12, 4)->nullable();
          $table->decimal('process_loss', 12, 4)->nullable();
          $table->decimal('req_cons', 12, 4)->nullable();
          $table->decimal('rate', 12, 4)->nullable();
          $table->decimal('amount', 12, 4)->nullable();
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
        Schema::dropIfExists('mkt_cost_fabric_cons');
    }
}
