<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubInbMarketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_inb_marketings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedTinyInteger('production_area_id')->nullable();
            $table->unsignedInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedInteger('teammember_id')->unsigned();
            $table->foreign('teammember_id')->references('id')->on('teammembers')->onDelete('cascade');//marketer
            $table->integer('buyer_id')->nullable()->unsigned();//customer
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('buyer_branch_id')->nullable(); // from 31/05/2022
            $table->foreign('buyer_branch_id')->references('id')->on('buyer_branches')->onDelete('cascade');
            $table->unsignedInteger('currency_id')->nullable();
            $table->date('mkt_date')->nullable();
            $table->string('refered_by',300)->nullable();
            //$table->string('contact',300)->nullable(); from 31/05/2022
            //$table->string('contact_no',100)->nullable();
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
        Schema::dropIfExists('sub_inb_marketings');
    }
}
