<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpDocSubAcceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_doc_sub_accepts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_exp_lc_id')->unsigned();      
            $table->foreign('local_exp_lc_id')->references('id')->on('local_exp_lcs')->onDelete('cascade');
            $table->date('submission_date');
            $table->string('courier_recpt_no',100)->nullable();
            $table->string('courier_company',300)->nullable();
            $table->string('submitted_by',200)->nullable();
            $table->date('accept_receive_date')->nullable();
            $table->string('remarks',400)->nullable();
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
        Schema::dropIfExists('local_exp_doc_sub_accepts');
    }
}
