<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdGmtEmbRcvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_gmt_emb_rcvs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('receive_no'
        )->nullable();
            $table->string('party_challan_no')->nullable();
            $table->unsignedInteger('prod_gmt_dlv_to_emb_id')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('shiftname_id')->nullable();
            $table->date('receive_date');
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('prod_gmt_emb_rcvs');
    }
}
