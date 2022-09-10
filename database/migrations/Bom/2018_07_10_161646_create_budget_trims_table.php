<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetTrimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_trims', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('budget_id')->unsigned();
          $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
          $table->unsignedInteger('supplier_id')->unsigned();
          $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
          $table->unsignedInteger('itemclass_id');
          $table->string('description',250);
          $table->string('specification',250);
          $table->string('item_size',100);
          $table->string('sup_ref',100);
          $table->unsignedInteger('uom_id')->unsigned();
          $table->decimal('cons', 12, 4);
          $table->decimal('rate', 12, 4);
          $table->decimal('amount', 12, 4);
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
        Schema::dropIfExists('budget_trims');
    }
}
