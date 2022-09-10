<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerBranchShipdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_branch_shipdays', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('buyer_id')->unsigned();
          $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
          $table->unsignedInteger('buyer_branch_id')->unsigned();
          $table->foreign('buyer_branch_id')->references('id')->on('buyer_branches')->onDelete('cascade');
          $table->string('day_name',100);
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
        Schema::dropIfExists('buyer_branch_shipdays');
    }
}
