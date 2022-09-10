<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapacityDistBuyerTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_dist_buyer_teams', function (Blueprint $table) {
          $table->increments('id')->integer();
          $table->unsignedInteger('capacity_dist_buyer_id');
          $table->foreign('capacity_dist_buyer_id')->references('id')->on('capacity_dist_buyers')->onDelete('cascade');
          $table->unsignedInteger('teammember_id');
          $table->foreign('teammember_id')->references('id')->on('teammembers')->onDelete('cascade');
          $table->decimal('distributed_percent', 3, 2);
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
        Schema::dropIfExists('capacity_dist_buyer_teams');
    }
}
