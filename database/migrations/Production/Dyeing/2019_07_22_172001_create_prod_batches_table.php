<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_color_id');
            $table->unsignedInteger('fabric_color_id');
            $table->unsignedInteger('colorrange_id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->date('batch_date');
            $table->unsignedInteger('batch_no');
            $table->unsignedInteger('batch_ext_no')->nullable();
            $table->unsignedInteger('lap_dip_no')->nullable();
            $table->unsignedInteger('machine_id');
            $table->unsignedTinyInteger('batch_for')
            $table->decimal('batch_wgt',14,4)->nullable();
            $table->decimal('fabric_wgt',14,4)->nullable();
            $table->unsignedTinyInteger('is_redyeing')->nullable()->default(0);
            $table->unsignedInteger('root_batch_id');
            $table->foreign('root_batch_id')->references('id')->on('prod_batches');
            $table->timestamp('loaded_at')->nullable();
            $table->date('load_date');
            $table->date('unload_date');
            $table->date('load_posting_date')->nullable();
            $table->decimal('tgt_hour',10,4)->nullable();
            $table->string('load_remarks',500)->nullable();
            $table->timestamp('unloaded_at')->nullable();
            $table->date('unload_posting_date')->nullable();
            $table->string('unload_remarks',500)->nullable();
            $table->unsignedSmallInteger('load_shift')->nullable();
            $table->unsignedSmallInteger('unload_shift')->nullable();
            $table->date('target_load_date')->nullable();
            $table->string('remarks',500)->nullable();
            
            $table->unsignedSmallInteger('created_by')->nullable();   
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip',20)->nullable();
            $table->string('updated_ip',20)->nullable();
            $table->string('deleted_ip',20)->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();   
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('unapproved_by')->nullable();
            $table->date('unapproved_at')->nullable();
            $table->unsignedInteger('unapproved_count')->nullable();
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
        Schema::dropIfExists('prod_batches');
    }
}
