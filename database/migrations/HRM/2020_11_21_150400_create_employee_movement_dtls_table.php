<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeMovementDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_movement_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_movement_id')->unsigned();
            $table->foreign('employee_movement_id')->references('id')->on('employee_movements')->onDelete('cascade');
            $table->timestamp('out_date_time');
            $table->timestamp('return_date_time')->nulable();
            $table->unsignedInteger('purpose_id')->nulable();
            $table->unsignedInteger('transport_mode_id')->nulable();
            $table->decimal('amount',12,4)->nulable();
            $table->string('work_detail',500);
            $table->string('destination',500)->nulable();
            $table->unsignedSmallInteger('created_by')->nulable();
            $table->timestamp('created_at')->nulable();
            $table->unsignedSmallInteger('updated_by')->nulable();
            $table->timestamp('updated_at')->nulable();
            $table->timestamp('deleted_at')->nulable();
            $table->string('created_ip',20)->nulable();
            $table->string('updated_ip',20)->nulable();
            $table->string('deleted_ip',20)->nulable();
            $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_movement_dtls');
    }
}
