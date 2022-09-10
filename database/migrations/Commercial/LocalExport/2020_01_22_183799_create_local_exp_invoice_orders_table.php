<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpInvoiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_invoice_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_exp_invoice_id')->unsigned();
            $table->foreign('local_exp_invoice_id')->references('id')->on('local_exp_invoices')->onDelete('cascade');
            $table->unsignedInteger('local_exp_pi_order_id');
            $table->foreign('local_exp_pi_order_id','invoicelocalpiorderid')->references('id')->on('local_exp_pi_orders')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->decimal('rate',10,6);
            $table->decimal('amount',14,4);
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
        Schema::dropIfExists('local_exp_invoice_orders');
    }
}
