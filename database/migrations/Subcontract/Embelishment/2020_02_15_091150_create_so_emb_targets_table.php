<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoEmbTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_emb_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id');
            $table->foreign('buyer_id','embgetbuyer')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id','embgetcompany')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('emb_id');
            $table->foreign('emb_id','embiergetemb')->references('id')->on('embelishments')->onDelete('cascade');
            $table->date('target_date')->nullable();
            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',10,6)->nullable();
            $table->date('execute_month')->nullable();
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
        Schema::dropIfExists('so_emb_targets');
    }
}
