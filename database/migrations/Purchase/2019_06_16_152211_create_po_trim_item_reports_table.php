<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoTrimItemReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_trim_item_reports', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_trim_item_id');
          $table->foreign('po_trim_item_id')->references('id')->on('po_trim_items')->onDelete('cascade');
          $table->unsignedInteger('sensivity_id');
		      $table->unsignedInteger('sales_order_id');
          $table->foreign('sales_order_id')->references('id')->on('sales_orders');

          $table->unsignedInteger('style_color_id')->nullable();
          $table->foreign('style_color_id')->references('id')->on('style_colors');

          $table->unsignedInteger('style_size_id')->nullable();
          $table->foreign('style_size_id')->references('id')->on('style_sizes');

          $table->unsignedInteger('trim_color')->nullable();
          $table->unsignedInteger('measurment')->nullable();
          $table->string('description',250)->nullable();
          
          $table->decimal('qty',14,4);
		      $table->decimal('rate', 14, 4);
          $table->decimal('amount', 14, 4);
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
        Schema::dropIfExists('po_trim_item_reports');
    }
}
