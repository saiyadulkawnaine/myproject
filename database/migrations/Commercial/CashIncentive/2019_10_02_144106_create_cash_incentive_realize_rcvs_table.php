<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveRealizeRcvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_realize_rcvs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_realize_id')->unsigned();
            $table->foreign('cash_incentive_realize_id')->references('id')->on('cash_incentive_realizes')->onDelete('cascade');
            $table->integer('commercial_head_id')->unsigned();
            $table->foreign('commercial_head_id')->references('id')->on('commercial_heads')->onDelete('cascade');
            $table->date('receive_date');
            //$table->unsignedInteger('account_head_id');
            $table->decimal('amount', 14, 4);
            $table->decimal('tax_percent', 10, 4);
            $table->string('remarks', 500)->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('cash_incentive_realize_rcvs');
    }
}
