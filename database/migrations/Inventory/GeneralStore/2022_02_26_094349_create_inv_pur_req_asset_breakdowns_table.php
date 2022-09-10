<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvPurReqAssetBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_pur_req_asset_breakdowns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_pur_req_id');
            $table->foreign('inv_pur_req_id','reqId')->references('id')->on('inv_pur_reqs')->onDelete('cascade');
            $table->unsignedInteger('asset_breakdown_id');
            $table->foreign('asset_breakdown_id')->references('id')->on('asset_breakdowns')->onDelete('cascade');
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
        Schema::dropIfExists('inv_pur_req_asset_breakdowns');
    }
}
