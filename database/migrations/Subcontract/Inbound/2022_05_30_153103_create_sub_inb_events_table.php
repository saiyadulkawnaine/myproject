<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubInbEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_inb_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_inb_marketing_id')->unsigned();
            $table->foreign('sub_inb_marketing_id')->references('id')->on('sub_inb_marketings')->onDelete('cascade');
            $table->date('meeting_date');
            $table->unsignedInteger('meeting_type_id');
            $table->string('self_participants',1000);
            $table->string('customer_participants',1000);
            $table->date('next_meeting_date');
            //$table->string('next_action_plan',1500)->nullable();
            $table->string('remarks',1500)->nullable();
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
        Schema::dropIfExists('sub_inb_events');
    }
}
