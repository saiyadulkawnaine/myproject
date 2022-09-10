<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlDyeingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pl_dyeing_items', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('pl_dyeing_id');
            $table->foreign('pl_dyeing_id')->references('id')->on('pl_dyeings')->onDelete('cascade');
            $table->unsignedInteger('so_dyeing_ref_id');
            $table->foreign('so_dyeing_ref_id')->references('id')->on('so_dyeing_refs')->onDelete('cascade');
            $table->unsignedInteger('colorrange_id')->nullable();
            $table->unsignedInteger('gsm_weight');
            $table->string('dia');
            $table->string('measurment');
            $table->string('stitch_length',200)->nullable();
            $table->string('spandex_stitch_length',200)->nullable();
            $table->decimal('draft_ratio',12,4)->nullable();
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
        Schema::dropIfExists('pl_dyeing_items');
    }
}
