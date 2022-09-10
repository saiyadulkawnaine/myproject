<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWstudyLineSetupDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wstudy_line_setup_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('wstudy_line_setup_id')->unsigned();
            $table->foreign('wstudy_line_setup_id')->references('id')->on('wstudy_line_setups')->onDelete('cascade');
            $table->integer('style_id')->unsigned();
            $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
            $table->unsignedInteger('operator');
            $table->unsignedInteger('helper');
            $table->string('line_chief')->nullable();
            $table->decimal('working_hour', 10, 6);
            $table->decimal('overtime_hour', 10, 6);
            $table->decimal('total_mnt', 12, 4);
            $table->decimal('target_per_hour', 10, 6);
            $table->string('sewing_start_at', 100)->nullable();
            $table->string('sewing_end_at', 100)->nullable();
            $table->string('lunch_start_at', 100)->nullable();
            $table->string('lunch_end_at', 100)->nullable();
            $table->string('tiffin_start_at', 100)->nullable();
            $table->string('tiffin_end_at', 100)->nullable();
            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('wstudy_line_setup_dtls');
    }
}
