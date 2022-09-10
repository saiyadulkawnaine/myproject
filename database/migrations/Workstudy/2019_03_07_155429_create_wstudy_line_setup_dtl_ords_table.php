<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWstudyLineSetupDtlOrdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wstudy_line_setup_dtl_ords', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wstudy_line_setup_dtl_id')->unsigned();
            $table->foreign('wstudy_line_setup_dtl_id')->references('id')->on('wstudy_line_setup_dtls')->onDelete('cascade');
            $table->integer('sales_order_id')->unsigned();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedInteger('qty');
            $table->decimal('prod_hour');
            $table->string('remarks',500)->nullable();
            $table->integer('style_gmt_id')->unsigned();
            $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->string('sewing_start_at',100)->nullable();
            $table->string('sewing_end_at',100)->nullable();
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
        Schema::dropIfExists('wstudy_line_setup_dtl_ords');
    }
}
