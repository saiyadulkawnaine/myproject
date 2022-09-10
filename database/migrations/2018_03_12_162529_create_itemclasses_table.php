<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemclassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemclasses', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('itemcategory_id');
            $table->foreign('itemcategory_id')->references('id')->on('itemcategories')->onDelete('cascade');
            $table->string('name',100);
            $table->string('code',5)->nullable();
            $table->unsignedTinyInteger('trims_type_id')->nullable();
            $table->unsignedTinyInteger('item_nature_id');
            $table->unsignedTinyInteger('uomclass_id');
            $table->unsignedTinyInteger('costing_uom_id');
            $table->unsignedTinyInteger('calculator_type_id')->nullable();
            $table->unsignedSmallInteger('pre_account_req_id');
            $table->unsignedSmallInteger('sensivity_id');
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
        Schema::dropIfExists('itemclasses');
    }
}
