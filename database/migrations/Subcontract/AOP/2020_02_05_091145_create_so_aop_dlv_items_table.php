<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopDlvItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_dlv_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_aop_dlv_id')->nullable()->unsigned();
            $table->foreign('so_aop_dlv_id','soaopdlvidcc')->references('id')->on('so_aop_dlvs')->onDelete('cascade');
            
            $table->integer('so_aop_ref_id')->nullable()->unsigned();
            $table->foreign('so_aop_ref_id','soaoprefidcc')->references('id')->on('so_aop_refs');
            
            $table->integer('design_no')->unsigned()->nullable();
            $table->string('design_name',100)->nullable();
            $table->string('fin_dia',100)->nullable();
            $table->integer('fin_gsm')->unsigned()->nullable();
            $table->decimal('grey_used',14,4);
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);
            $table->decimal('no_of_roll',14,4);
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
        Schema::dropIfExists('so_aop_dlv_items');
    }
}
