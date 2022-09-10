<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingBomOverheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_bom_overheads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_dyeing_bom_id')->nullable()->unsigned();
            $table->foreign('so_dyeing_bom_id')->references('id')->on('so_dyeing_boms')->onDelete('cascade');
            $table->integer('acc_chart_ctrl_head_id')->nullable()->unsigned();
            $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads');
            $table->decimal('cost_per',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
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
        Schema::dropIfExists('so_dyeing_bom_overheads');
    }
}
