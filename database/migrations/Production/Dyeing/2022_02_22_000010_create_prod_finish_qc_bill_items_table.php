<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdFinishQcbillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_finish_qc_bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_finish_qc_bill_id')->unsigned();
            $table->foreign('prod_finish_qc_bill_id')->references('id')->on('prod_finish_qc_bills')->onDelete('cascade');
            $table->unsignedInteger('autoyarn_id');
            $table->string('process_name')->nullable();
            $table->decimal('amount', 14, 4);
            $table->decimal('qty', 14, 4);
            $table->decimal('rate', 10, 6);
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('prod_finish_qc_bill_items');
    }
}
