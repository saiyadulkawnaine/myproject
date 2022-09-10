<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostStandardHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_standard_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cost_standard_id')->unsigned();
            $table->foreign('cost_standard_id')->references('id')->on('cost_standards')->onDelete('cascade');
            $table->integer('acc_chart_ctrl_head_id')->unsigned();
            $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads');
            $table->decimal('cost_per',14,4);
            $table->string('remarks',300)->nullable();
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
        Schema::dropIfExists('cost_standard_heads');
    }
}
