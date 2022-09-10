<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJhuteSaleDlvOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jhute_sale_dlv_order_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jhute_sale_dlv_order_id');
            $table->foreign('jhute_sale_dlv_order_id','jhuteId')->references('id')->on('jhute_sale_dlv_orders')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount', 14, 4)->nullable();
             $table->unsignedInteger('receive_by_id');
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
        Schema::dropIfExists('jhute_sale_dlv_order_payments');
    }
}