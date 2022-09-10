<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRqYarnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rq_yarn_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rq_yarn_fabrication_id');
            $table->foreign('rq_yarn_fabrication_id')->references('id')->on('rq_yarn_fabrications')->onDelete('cascade');
            $table->unsignedInteger('inv_yarn_item_id');
            $table->foreign('inv_yarn_item_id')->references('id')->on('inv_yarn_items')->onDelete('cascade');
            $table->decimal('qty',14,4);
            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('rq_yarn_items');
    }
}
