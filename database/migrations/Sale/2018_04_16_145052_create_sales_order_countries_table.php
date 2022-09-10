<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_countries', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('sale_order_id');
            $table->foreign('sale_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedInteger('style_gmt_id');
            $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->unsignedTinyInteger('fabric_looks');
            $table->date('cut_off_date')->nullable();
            $table->unsignedTinyInteger('cut_off')->nullable();
            $table->date('country_ship_date');
            $table->unsignedTinyInteger('breakdown_basis');
            $table->unsignedInteger('qty');
            $table->decimal('sam', 12, 4);
            $table->decimal('no_of_carton', 12, 4)->nullable();
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
        Schema::dropIfExists('sales_order_countries');
    }
}
