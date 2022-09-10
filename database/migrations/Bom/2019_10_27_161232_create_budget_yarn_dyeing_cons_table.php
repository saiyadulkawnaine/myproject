<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetYarnDyeingConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_yarn_dyeing_cons', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('budget_yarn_dyeing_id');
          $table->foreign('budget_yarn_dyeing_id')->references('id')->on('budget_yarn_dyeings')->onDelete('cascade');
		      $table->unsignedInteger('sales_order_id');

          $table->unsignedInteger('style_fabrication_stripe_id');
          $table->foreign('style_fabrication_stripe_id')->references('id')->on('style_fabrication_stripes');
          $table->unsignedInteger('yarn_color_id',10)->nullable();
          $table->decimal('measurment', 8, 4);
          $table->unsignedSmallInteger('feeder');
          
          $table->decimal('bom_qty', 12, 4)->nullable();
          $table->decimal('rate', 12, 4)->nullable();
          $table->decimal('amount', 12, 4)->nullable();

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
        Schema::dropIfExists('budget_yarn_dyeing_cons');
    }
}
