<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_transfers', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('produced_company_id')->nullable();
            $table->unsignedInteger('sales_order_id');
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedInteger('style_gmt_id')->unique();
            $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
            $table->date('date_from');
            $table->date('date_to');
            $table->unsignedInteger('process_id');
            $table->decimal('qty',14,4)->nullable();
            $table->unsignedSmallInteger('prod_source_id')->nullable();
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
        Schema::dropIfExists('target_transfers');
    }
}
