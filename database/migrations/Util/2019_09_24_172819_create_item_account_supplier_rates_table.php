<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAccountSupplierRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_account_supplier_rates', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('item_account_supplier_id');
            $table->foreign('item_account_supplier_id')->references('id')->on('item_account_suppliers')->onDelete('cascade');
            $table->date('date_from');
            $table->date('date_to');
            $table->decimal('dom_rate',10,4);
            $table->decimal('foreign_rate',10,4);
            $table->unsignedInteger('dom_currency_id');
            $table->unsignedInteger('foreign_currency_id');
            $table->decimal('exch_rate',10,4);
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
        Schema::dropIfExists('item_account_supplier_rates');
    }
}
