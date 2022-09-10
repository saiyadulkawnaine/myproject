<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdAopBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_aop_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_aop_id')->nullable()->unsigned();
            $table->foreign('so_aop_id')->references('id')->on('so_aops')->onDelete('cascade');

            
            $table->unsignedInteger('batch_no');
            $table->date('batch_date');
            $table->unsignedInteger('design_no')->nullable();
            $table->unsignedInteger('batch_color_id');

            $table->unsignedTinyInteger('batch_for');
            $table->decimal('paste_wgt',14,4);
            $table->decimal('fabric_wgt',14,4);
            
            $table->date('target_load_date')->nullable();
            $table->string('remarks',500)->nullable();

            $table->unsignedSmallInteger('approved_by')->nullable();   
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('unapproved_by')->nullable();
            $table->date('unapproved_at')->nullable();
            $table->unsignedInteger('unapproved_count')->nullable();
            
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
        Schema::dropIfExists('prod_aop_batches');
    }
}
