<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvTrimItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_trim_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();

            $table->unsignedInteger('itemclass_id')->unsigned();
            $table->foreign('itemclass_id')->references('id')->on('itemclasses');
            $table->unsignedInteger('color_id')->nullable();
            $table->string('measurment',100);
            $table->string('description',250);
            $table->unique(['itemclass_id', 'color_id','measurment','description'],'trim_item_uq');

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
        Schema::dropIfExists('inv_trim_items');
    }
}
