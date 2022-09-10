<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpDocMaturityDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imp_doc_maturity_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('imp_doc_maturity_id')->unsigned();
		  	$table->foreign('imp_doc_maturity_id')->references('id')->on('imp_doc_maturities')->onDelete('cascade');
		  	$table->integer('imp_doc_accept_id')->unsigned();
            $table->foreign('imp_doc_accept_id','docacceptid')->references('id')->on('imp_doc_accepts')->onDelete('cascade');
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
        Schema::dropIfExists('imp_doc_maturity_dtls');
    }
}
