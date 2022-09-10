<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtCartonDetailQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_carton_detail_qties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_gmt_carton_detail_id')->unsigned();
            $table->foreign('prod_gmt_carton_detail_id','prodgmtcartondetailid')->references('id')->on('prod_gmt_carton_details')->onDelete('cascade');
            $table->integer('style_gmt_color_size_id')->unsigned();
            $table->foreign('style_gmt_color_size_id','stylegmtcolorsizeid')->references('id')->on('style_gmt_color_sizes')->onDelete('cascade');
            $table->integer('style_pkg_ratio_id')->unsigned();
            $table->foreign('style_pkg_ratio_id','stylepkgratioid')->references('id')->on('style_pkg_ratios')->onDelete('cascade');
            $table->unsignedInteger('qty');
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
        Schema::dropIfExists('prod_gmt_carton_detail_qties');
    }
}
