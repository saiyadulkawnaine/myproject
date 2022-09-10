<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpLcScPisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_lc_sc_pis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_lc_sc_id')->unsigned();       
            $table->foreign('exp_lc_sc_id')->references('id')->on('exp_lc_scs')->onDelete('cascade');
            
            $table->integer('exp_pi_id')->unsigned();
            $table->foreign('exp_pi_id')->references('id')->on('exp_pis')->onDelete('cascade');

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
        Schema::dropIfExists('exp_lc_sc_pis');
    }
}
