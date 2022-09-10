<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderGmtColorSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_gmt_color_sizes', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('sale_order_id');
            $table->unsignedInteger('sale_order_country_id');
            $table->unsignedInteger('style_gmt_color_size_id');
            $table->foreign('style_gmt_color_size_id', 'style_gm_co_si_so_gm_co_si_fk')->references('id')->on('style_gmt_color_sizes')->onDelete('cascade');
            $table->unsignedInteger('style_gmt_id');
            $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->unsignedInteger('style_color_id');
            $table->foreign('style_color_id')->references('id')->on('style_colors')->onDelete('cascade');
            $table->unsignedInteger('style_size_id');
            $table->foreign('style_size_id')->references('id')->on('style_sizes')->onDelete('cascade');
            $table->string('article_no', 100)->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->decimal('rate', 10, 4)->nullable();
            $table->decimal('amount', 14, 4)->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('sales_order_gmt_color_sizes');
    }
}
