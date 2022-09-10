<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurYarnBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pur_yarn_budgets', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('purchase_order_id');
          $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
          $table->unsignedInteger('pur_yarn_id');
          $table->foreign('pur_yarn_id')->references('id')->on('pur_yarns')->onDelete('cascade');
          $table->unsignedInteger('budget_yarn_id');
          $table->foreign('budget_yarn_id')->references('id')->on('budget_yarns')->onDelete('cascade');
          $table->decimal('qty',12,4)->nullable();
		      $table->decimal('rate',12,4)->nullable();
          $table->decimal('amount',12,4)->nullable();
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
        Schema::dropIfExists('pur_yarn_budgets');
    }
}
