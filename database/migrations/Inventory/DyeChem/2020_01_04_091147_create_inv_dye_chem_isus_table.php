<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvDyeChemIsusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dye_chem_isus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('issue_no');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('inv_dye_chem_isu_rq_id');
            $table->foreign('inv_dye_chem_isu_rq_id')->references('id')->on('inv_dye_chem_isu_rqs')->onDelete('cascade');
            $table->date('issue_date');
            $table->string('remarks', 500)->nullable();
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
        Schema::dropIfExists('inv_dye_chem_isus');
    }
}
