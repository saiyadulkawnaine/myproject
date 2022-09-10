<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerDevelopmentDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_development_docs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('buyer_development_id')->unsigned();
            $table->foreign('buyer_development_id','buyerdevelopmentiddoc')->references('id')->on('buyer_developments')->onDelete('cascade');
            $table->string('original_name');
            $table->string('file_src');
            $table->unsignedTinyInteger('file_type_id');
            $table->string('remarks',250)->nullable();
            
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
        Schema::dropIfExists('buyer_development_docs');
    }
}
