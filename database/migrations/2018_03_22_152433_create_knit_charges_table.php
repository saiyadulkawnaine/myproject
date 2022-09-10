<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnitChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knit_charges', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
          $table->unsignedInteger('gmtspart_id')->unsigned();
          $table->foreign('gmtspart_id')->references('id')->on('gmtsparts')->onDelete('cascade');
          $table->unsignedInteger('construction_id')->unsigned();
          $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
          $table->unsignedInteger('composition_id')->unsigned();
          $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
		  $table->unsignedInteger('autoyarn_id')->unsigned();
          $table->foreign('autoyarn_id')->references('id')->on('autoyarns')->onDelete('cascade');
          $table->unsignedTinyInteger('from_gsm');
          $table->unsignedTinyInteger('to_gsm');
          $table->unsignedTinyInteger('gauge')->nullable();
          $table->unsignedTinyInteger('fabric_shape_id')->nullable();
		  $table->unsignedTinyInteger('fabric_look_id')->nullable();
          $table->unsignedInteger('yarncount_id');
          $table->foreign('yarncount_id')->references('id')->on('yarncounts')->onDelete('cascade');
          $table->decimal('in_house_rate', 8, 4);
          $table->unsignedInteger('uom_id')->unsigned();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
          $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('knit_charges');
    }
}
