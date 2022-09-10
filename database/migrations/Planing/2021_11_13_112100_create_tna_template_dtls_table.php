<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnaTemplateDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_template_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tna_template_id')->unsigned();
            $table->foreign('tna_template_id')->references('id')->on('tna_templates')->onDelete('cascade');
            $table->integer('tnatask_id')->unsigned();
            $table->foreign('tnatask_id','taskName')->references('id')->on('tnatasks')->onDelete('cascade');
            $table->unsignedInteger('lead_days');
            $table->unsignedInteger('lag_days')->nullable();
            $table->unsignedInteger('depending_task_id')->nullable();
            $table->unsignedInteger('start_end_basis_id')->nullable();
            $table->unsignedInteger('start_end_basis_days')->nullable();
            $table->unsignedInteger('start_reminder_days')->nullable();
            $table->unsignedInteger('end_reminder_days')->nullable();
            $table->unsignedInteger('sort_id')->nullable();
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
        Schema::dropIfExists('tna_template_dtls');
    }
}
