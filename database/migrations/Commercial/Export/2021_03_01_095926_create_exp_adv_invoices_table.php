<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpAdvInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_adv_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_lc_sc_id')->unsigned();       
            $table->foreign('exp_lc_sc_id')->references('id')->on('exp_lc_scs')->onDelete('cascade');
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->decimal('invoice_value',14,4)->nullable();
            $table->unsignedInteger('invoice_qty')->nullable();
            $table->string('exp_form_no',100)->nullable();
            $table->date('exp_form_date')->nullable();
            $table->date('actual_ship_date')->nullable();
            $table->integer('country_id')->nullable()->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('category_no',100)->nullable();
            //$table->decimal('net_inv_value',14,4)->nullable();
            $table->string('file_src',300)->nullable();
            $table->string('shipping_mark',300)->nullable();
            $table->decimal('net_wgt_exp_qty',12,4)->nullable();
            $table->decimal('gross_wgt_exp_qty',12,4)->nullable();
            $table->decimal('cbm',12,4)->nullable();
            $table->decimal('total_ctn_qty',12)->nullable();
            $table->string('remarks',500)->nullable();
            $table->string('rex_declaration',700)->nullable();
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
        Schema::dropIfExists('exp_adv_invoices');
    }
}
