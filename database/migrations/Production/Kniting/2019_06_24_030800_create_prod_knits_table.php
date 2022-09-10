<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdKnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_knits', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('prod_no');
        $table->date('prod_date');
        $table->unsignedSmallInteger('basis_id');
        $table->string('challan_no',100)->nullable();
        $table->unsignedInteger('supplier_id');
        $table->foreign('supplier_id')->references('id')->on('suppliers');
        $table->unsignedInteger('location_id')->nullable();
        $table->unsignedInteger('floor_id')->nullable();
        $table->unsignedInteger('shift_id')->nullable();
        $table->unsignedSmallInteger('created_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->unsignedSmallInteger('updated_by')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->string('created_ip',20)->nullable();
        $table->string('updated_ip',20)->nullable();
        $table->string('deleted_ip',20)->nulable();
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
        Schema::dropIfExists('prod_knits');
    }
}
