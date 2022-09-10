<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_developments', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedInteger('buyer_id')->unsigned();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('teammember_id');
            $table->unsignedInteger('product_type_id');
            $table->string('end_user_market',250);
            $table->string('existing_supplier',250)->nullable();
            $table->string('credit_rating',250);
            $table->unsignedInteger('credit_type_id');
            $table->unsignedInteger('pay_term_id');
            $table->string('penalty_clause',250);
            $table->string('compliance_req',250);
            $table->string('remarks',250)->nullable();
            $table->unsignedInteger('status_id')->nullable();
            
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
        Schema::dropIfExists('buyer_developments');
    }
}
