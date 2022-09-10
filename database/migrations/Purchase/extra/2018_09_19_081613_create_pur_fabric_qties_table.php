<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurFabricQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pur_fabric_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('pur_fabric_id');
          $table->foreign('pur_fabric_id')->references('id')->on('pur_fabrics')->onDelete('cascade');
		      $table->unsignedInteger('budget_fabric_con_id');
          $table->foreign('budget_fabric_con_id')->references('id')->on('budget_fabric_cons')->onDelete('cascade');
          $table->decimal('qty',12,4);
		      $table->decimal('rate', 12, 4);
          $table->decimal('amount', 12, 4);
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
        Schema::dropIfExists('pur_fabric_qties');
    }
}
