<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlDyeingItemQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pl_dyeing_item_qties', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('pl_dyeing_item_id');
            $table->foreign('pl_dyeing_item_id')->references('id')->on('pl_dyeing_items')->onDelete('cascade');
            $table->date('pl_date');
            $table->decimal('qty',14,4);
            $table->decimal('filled',14,4);
            $table->decimal('free',14,4);
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
        Schema::dropIfExists('pl_dyeing_item_qties');
    }
}
