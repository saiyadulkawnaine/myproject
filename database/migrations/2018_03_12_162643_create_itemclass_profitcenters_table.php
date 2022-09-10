<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemclassProfitcentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemclass_profitcenters', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('itemclass_id');
            $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
            $table->unsignedInteger('profitcenter_id');
            $table->foreign('profitcenter_id')->references('id')->on('profitcenters')->onDelete('cascade');
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
        Schema::dropIfExists('itemclass_profitcenters');
    }
}
