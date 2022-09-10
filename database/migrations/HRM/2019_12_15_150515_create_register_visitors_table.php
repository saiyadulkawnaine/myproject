<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200);
            $table->string('contact_no',150);
            $table->string('organization_dtl',400);
            $table->string('arrival_time',50);
            $table->string('departure_time',50);
            $table->date('arrival_date');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('approve_user_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('purpose',500)->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('register_visitors');
    }
}
