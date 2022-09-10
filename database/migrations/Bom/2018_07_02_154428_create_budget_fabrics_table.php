<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFabricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_fabrics', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('budget_id')->unsigned();
          $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
		  $table->unsignedInteger('style_fabrication_id');
          $table->foreign('style_fabrication_id')->references('id')->on('style_fabrications')->onDelete('cascade');
          $table->unsignedTinyInteger('fabric_type')->nullable();
          $table->unsignedInteger('gmtspart_id')->nullable();
          $table->unsignedTinyInteger('fabric_nature_id')->nullable();
          $table->unsignedTinyInteger('fabric_look_id')->nullable();
          $table->unsignedInteger('autoyarn_id')->nullable();
          $table->unsignedInteger('gsm_weight')->nullable();
          $table->unsignedInteger('colorrange_id')->nullable();
          $table->unsignedInteger('fabric_shape_id')->nullable();
          $table->unsignedTinyInteger('material_source_id')->nullable();
          $table->unsignedTinyInteger('cons_basis')->nullable();
          $table->unsignedInteger('uom_id')->nullable();
		  $table->unsignedInteger('supplier_id')->unsigned();
          $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
          $table->decimal('fabric_cons', 12, 4)->nullable();
          $table->decimal('rate', 12, 4)->nullable();
          $table->decimal('amount', 12, 4)->nullable();
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
        Schema::dropIfExists('budget_fabrics');
    }
}
