<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetServiceRepairPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_service_repair_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("asset_service_repair_id");
            $table->foreign("asset_service_repair_id")->references('id')->on("asset_service_repairs")->onDelete('cascade');
            $table->unsignedInteger("item_account_id");
            $table->foreign("item_account_id")->references("id")->on("item_accounts")->onDelete("cascade");
            $table->unsignedInteger("qty");
            $table->string('remarks', 400)->nullable();

            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('asset_service_repair_parts');
    }
}
