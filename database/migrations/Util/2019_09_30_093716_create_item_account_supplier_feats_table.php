<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAccountSupplierFeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_account_supplier_feats', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('item_account_supplier_id');
            $table->foreign('item_account_supplier_id')->references('id')->on('item_account_suppliers')->onDelete('cascade');
            $table->unsignedSmallInteger('feature_point_id');
            $table->unsignedSmallInteger('available_id');
            $table->unsignedSmallInteger('mandatory_id');
            $table->string('values',150)->nullable();
            $table->string('remarks',400)->nullable();
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
        Schema::dropIfExists('item_account_supplier_feats');
    }
}
