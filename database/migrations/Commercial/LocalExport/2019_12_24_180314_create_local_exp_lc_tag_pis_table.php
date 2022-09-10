<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpLcTagPisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_lc_tag_pis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_exp_lc_id')->unsigned();      
            $table->foreign('local_exp_lc_id','locallcid')->references('id')->on('local_exp_lcs')->onDelete('cascade');
            
            $table->integer('local_exp_pi_id')->unsigned();
            $table->foreign('local_exp_pi_id','localexppiid')->references('id')->on('local_exp_pis')->onDelete('cascade');

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
        Schema::dropIfExists('local_exp_lc_tag_pis');
    }
}
