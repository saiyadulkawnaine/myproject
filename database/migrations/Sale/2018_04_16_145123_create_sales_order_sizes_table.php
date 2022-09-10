<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_sizes', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('job_id');
		  $table->unsignedInteger('sale_order_id');
		  $table->unsignedInteger('sale_order_country_id');
		  $table->unsignedInteger('sale_order_color_id');
		  $table->foreign('sale_order_color_id')->references('id')->on('sales_order_colors')->onDelete('cascade');
          $table->unsignedInteger('style_size_id');
          $table->foreign('style_size_id')->references('id')->on('style_sizes')->onDelete('cascade');
          $table->string('size_code',100)->nullable();
          $table->unsignedInteger('qty')->nullable();
          $table->decimal('rate', 10, 4)->nullable();
          $table->decimal('amount', 14, 4)->nullable();
          $table->unsignedSmallInteger('sort_id');
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
        Schema::dropIfExists('sales_order_sizes');
    }
}
