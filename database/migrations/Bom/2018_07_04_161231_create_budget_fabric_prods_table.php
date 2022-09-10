<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFabricProdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_fabric_prods', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('budget_id')->unsigned();
          $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
          $table->unsignedInteger('budget_fabric_id')->unsigned();
          $table->foreign('budget_fabric_id')->references('id')->on('budget_fabrics')->onDelete('cascade');
          $table->unsignedInteger('production_process_id');
          $table->decimal('cons', 12, 4);
		      $table->decimal('req_cons', 12, 4);
          $table->decimal('rate', 12, 4);
          $table->decimal('amount', 12, 4);
          $table->unsignedInteger('company_id')->unsigned();
          $table->decimal('overhead_rate', 12, 4)->nullable();
          $table->decimal('overhead_amount', 12, 4)->nullable();
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
        Schema::dropIfExists('budget_fabric_prods');
    }
}
