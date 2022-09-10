<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccChartCtrlHeadMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_chart_ctrl_head_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('acc_chart_ctrl_head_id');
            $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads')->onDelete('cascade');
            $table->unsignedInteger('acc_acumulate_ctrl_head_id');
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
        Schema::dropIfExists('acc_chart_ctrl_head_mappings');
    }
}
