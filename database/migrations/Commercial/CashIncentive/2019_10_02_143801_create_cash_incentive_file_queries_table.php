<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveFileQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_file_queries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_file_id')->unsigned();       
            $table->foreign('cash_incentive_file_id')->references('id')->on('cash_incentive_files')->onDelete('cascade');
            $table->date('query_date');
            $table->string('query_remarks',500);
            //$table->unsignedSmallInteger('query_detail_id');
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
        Schema::dropIfExists('cash_incentive_file_queries');
    }
}
