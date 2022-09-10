<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_branches', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('buyer_id')->unsigned();
          $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
          $table->unsignedInteger('country_id')->unsigned();
          $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
          $table->string('name',100);
          $table->string('code',5);
          $table->string('contact_person',100)->nullable();
          $table->string('email',100)->nullable();
          $table->string('designation',100)->nullable();
          $table->string('address',250)->nullable();
          $table->string('shipment_day',100)->nullable();
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
        Schema::dropIfExists('buyer_branches');
    }
}
