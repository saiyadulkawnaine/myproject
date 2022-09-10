<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubInbServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_inb_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sub_inb_marketing_id')->unsigned();
            $table->foreign('sub_inb_marketing_id')->references('id')->on('sub_inb_marketings')->onDelete('cascade');
            $table->unsignedInteger('colorrange_id');
            $table->foreign('colorrange_id')->references('id')->on('colorranges')->onDelete('cascade');
            $table->unsignedInteger('uom_id')->nullable();
            // from 2019
      //       $table->unsignedTinyInteger('dyeing_type_id')->nullable();
      //       $table->unsignedTinyInteger('fabric_shape_id')->nullable();
      //       $table->unsignedTinyInteger('embelishment_type_id');
		    // $table->foreign('embelishment_type_id')->references('id')->on('embelishment_types')->onDelete('cascade');//aop_type
      //       $table->unsignedTinyInteger('from_coverage');
      //       $table->unsignedTinyInteger('to_coverage');
      //       $table->unsignedTinyInteger('from_impression');  
      //       $table->unsignedTinyInteger('to_impression');        
      //       $table->unsignedInteger('construction_id');
      //       $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
      //       $table->unsignedTinyInteger('fabric_look_id')->nullable();
      //       $table->unsignedInteger('gmtspart_id');
      //       $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');    
      //       $table->unsignedInteger('yarncount_id');
      //       $table->foreign('yarncount_id')->references('id')->on('yarncounts')->onDelete('cascade');
      //       $table->unsignedTinyInteger('gauge')->nullable();
      //       $table->unsignedTinyInteger('from_gsm')->nullable();
      //       $table->unsignedTinyInteger('to_gsm')->nullable();
            //  To 31/05/2022
            $table->unsignedInteger('qty')->nullable();//projected qty
            $table->decimal('rate', 12, 4)->nullable();//quoted rate
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('standard_rate',12,4)->nullable();
            $table->date('est_delv_date')->nullable()->nullable();
            $table->unsignedInteger('sample_req_qty')->nullable();
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
        Schema::dropIfExists('sub_inb_services');
    }
}
