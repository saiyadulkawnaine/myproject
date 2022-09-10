<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWstudyLineSetupMinAdjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wstudy_line_setup_min_adjs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wstudy_line_setup_dtl_id')->unsigned();
            $table->foreign('wstudy_line_setup_dtl_id')->references('id')->on('wstudy_line_setup_dtls')->onDelete('cascade');
            $table->unsignedInteger('minute_adj_reason_id');
            $table->unsignedInteger('no_of_hour');
            $table->unsignedInteger('no_of_resource');
            $table->unsignedInteger('no_of_minute');
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
        Schema::dropIfExists('wstudy_line_setup_min_adjs');
    }
}
