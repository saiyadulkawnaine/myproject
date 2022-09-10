<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',150)->unique();
          $table->string('code',5);
          $table->unsignedInteger('gmt_category_id');
          $table->unsignedTinyInteger('dept_category_id');
          $table->unsignedInteger('gmtspart_id')->unsigned();
          $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');
          $table->unsignedInteger('fabrication_id')->nullable();
          $table->unsignedTinyInteger('smv_basis_id');
          $table->unsignedInteger('resource_id')->unsigned();
          $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
          $table->decimal('machine_smv', 8, 4);
          $table->decimal('manual_smv', 8, 4);
          $table->unsignedTinyInteger('productionarea_id')->nullable();
          $table->decimal('seam_length', 8, 4)->nullable();
          $table->unsignedSmallInteger('operation_type_id')->nullable();
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
        Schema::dropIfExists('operations');
    }
}
