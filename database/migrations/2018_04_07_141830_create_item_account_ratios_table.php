<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAccountRatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_account_ratios', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('item_account_id')->unsigned();
          $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
          $table->unsignedInteger('composition_id')->unsigned();
          $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
          $table->string('ratio',8,2)->nullable();
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
        Schema::dropIfExists('item_account_ratios');
    }
}
