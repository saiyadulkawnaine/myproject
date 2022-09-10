<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaProgressDelayDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_progress_delay_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tna_progress_delay_id')->unsigned();
            $table->foreign('tna_progress_delay_id')->references('id')->on('tna_progress_delays')->onDelete('cascade');
            $table->unsignedInteger('employee_h_r_id')->nulable();
            $table->foreign('employee_h_r_id')->references('id')->on('employee_h_rs')->onDelete('cascade');
            $table->string('cause_of_delay',400);
            $table->string('impact',400)->nullable();
            $table->string('action_taken',400);
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
        Schema::dropIfExists('tna_progress_delay_dtls');
    }
}
