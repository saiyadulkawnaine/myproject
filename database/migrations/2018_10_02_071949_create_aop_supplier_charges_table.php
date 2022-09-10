<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAopSupplierChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aop_supplier_charges', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('aop_charge_id')->unsigned();
          $table->foreign('aop_charge_id')->references('id')->on('aop_charges')->onDelete('cascade');
          $table->unsignedInteger('supplier_id')->unsigned();
          $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
          $table->decimal('rate', 8, 4);
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
        Schema::dropIfExists('aop_supplier_charges');
    }
}
