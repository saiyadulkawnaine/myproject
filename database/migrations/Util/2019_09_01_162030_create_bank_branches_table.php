<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_id')->unsigned()->index();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->string('address',500);   
            $table->string('branch_name',50);   
            $table->string('contact',400);
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
        Schema::dropIfExists('bank_branches');
    }
}
