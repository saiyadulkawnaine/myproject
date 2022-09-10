<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvDyeChemIsuRqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dye_chem_isu_rqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable();
            $table->unsignedInteger('root_id')->nullable();
            $table->unsignedInteger('rq_no');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedInteger('prod_batch_id')->nullable();
            $table->foreign('prod_batch_id')->references('id')->on('prod_batches')->onDelete('cascade');
            $table->unsignedInteger('fabrication_id')->nullable();
            $table->string('fabric_desc', 1500)->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('rq_basis_id');
            $table->unsignedInteger('rq_against_id');
            $table->unsignedInteger('operator_id')->nullable();
            $table->unsignedInteger('incharge_id')->nullable();
            $table->date('rq_date');
            $table->decimal('liqure_ratio',14,4)->nullable();
            $table->decimal('liqure_wgt',14,4)->nullable();
            $table->unsignedInteger('design_no')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('colorrange_id');
            $table->decimal('paste_wgt',14,4)->nullable();
            $table->decimal('fabric_wgt',14,4)->nullable();
            $table->string('remarks', 500)->nullable();
            $table->unsignedInteger('prod_batch_finish_prog_id')->nullable();
            $table->foreign('prod_batch_finish_prog_id')->references('id')->on('prod_batch_finish_progs');
            $table->unsignedInteger('prod_aop_batch_id')->nullable();
            $table->foreign('prod_aop_batch_id')->references('id')->on('prod_aop_batches')/*->onDelete('cascade')*/;
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
        Schema::dropIfExists('inv_dye_chem_isu_rqs');
    }
}
