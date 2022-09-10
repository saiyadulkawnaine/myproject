<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlKnitItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pl_knit_items', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('pl_knit_id');
            $table->foreign('pl_knit_id')->references('id')->on('pl_knits')->onDelete('cascade');
            $table->unsignedInteger('so_knit_ref_id');
            $table->foreign('so_knit_ref_id')->references('id')->on('so_knit_refs')->onDelete('cascade');
            $table->unsignedInteger('colorrange_id')->nullable();
            $table->unsignedInteger('gsm_weight');
            $table->string('dia');
            $table->string('measurment');
            $table->string('stitch_length',200);
            $table->string('spandex_stitch_length',200);
            $table->decimal('draft_ratio',12,4);
            $table->unsignedInteger('no_of_feeder');
            $table->unsignedInteger('machine_id')->nullable();
            $table->unsignedInteger('machine_gg');
            $table->unsignedInteger('rpm');
            $table->unsignedInteger('no_of_needle');
            $table->unsignedInteger('hour');
            $table->decimal('expected_effi_per',12,4);
            $table->unsignedInteger('count');
            $table->decimal('capacity',12,4);
            $table->decimal('qty',14,4);
            $table->date('pl_start_date');
            $table->date('pl_end_date');
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('pl_knit_items');
    }
}
