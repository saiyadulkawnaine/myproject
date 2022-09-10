<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAccountSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_account_suppliers', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('item_account_id');
          $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade');
          $table->unsignedInteger('supplier_id');
          $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
          $table->string('custom_name',300);
          $table->unsignedInteger('country_id')->nullable();
          $table->unsignedInteger('supplier_point_id')->nullable();
          $table->string('prod_dosage',300)->nullable();
          $table->string('hs_code',100)->nullable();
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
        Schema::dropIfExists('item_account_suppliers');
    }
}
