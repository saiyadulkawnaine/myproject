<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetTrimDtmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_trim_dtms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('budget_trim_id');
            $table->foreign('budget_trim_id')->references('id')->on('budget_trims')->onDelete('cascade');
            $table->unsignedInteger('budget_fabric_id')->unsigned();
            $table->foreign('budget_fabric_id')->references('id')->on('budget_fabrics')->onDelete('cascade');
			$table->unsignedInteger('fabric_color')->unsigned();
            $table->decimal('qty',12,4);
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
        Schema::dropIfExists('budget_trim_dtms');
    }
}
