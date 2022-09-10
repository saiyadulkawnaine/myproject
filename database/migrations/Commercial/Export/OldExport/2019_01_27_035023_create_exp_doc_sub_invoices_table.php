<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpDocSubInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_doc_sub_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_doc_submission_id')->unsigned();
            $table->foreign('exp_doc_submission_id')->references('id')->on('exp_doc_submissions')->onDelete('cascade');
            $table->integer('exp_invoice_order_id')->unsigned();
            $table->foreign('exp_invoice_order_id')->references('id')->on('exp_invoice_orders')->onDelete('cascade');
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
        Schema::dropIfExists('exp_doc_sub_invoices');
    }
}
