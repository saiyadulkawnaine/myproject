<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gate_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('barcode_no_id');
            $table->unsignedInteger('challan_no')->nullable();
            $table->string('comments',500)->nullable();
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
        Schema::dropIfExists('gate_entries');
    }
}
