<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_cms', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('budget_id')->unsigned();
          $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unique();
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedInteger('method_id')->nullable();
          $table->decimal('amount', 12, 4)->nullable();
		      $table->decimal('bom_amount', 12, 4)->nullable();

          $table->decimal('smv', 8, 4);
          $table->decimal('sewing_effi_per', 8, 4);
          $table->decimal('cm_per_pcs', 8, 4);
          $table->decimal('cpm', 8, 4);
          $table->unsignedInteger('no_of_man_power');
          $table->unsignedInteger('prod_per_hour');

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
        Schema::dropIfExists('budget_cms');
    }
}
