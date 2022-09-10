<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvFinishFabRcvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_finish_fab_rcvs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_rcv_id');
            $table->foreign('inv_rcv_id')->references('id')->on('inv_rcvs')->onDelete('cascade');

            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('receive_no');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('receive_basis_id');
            $table->unsignedInteger('receive_against_id');
            $table->unsignedInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedInteger('currency_id');
            $table->decimal('exch_rate', 12, 4)->nullable();
            $table->unsignedInteger('prod_finish_dlv_id');
            $table->foreign('prod_finish_dlv_id','inv_finish_fab_rcvs_prod_fini_')->references('id')->on('prod_finish_dlvs')->onDelete('cascade');
            $table->unsignedInteger('po_fabric_id');
            $table->foreign('po_fabric_id')->references('id')->on('po_fabrics')->onDelete('cascade');
            $table->string('challan_no');
            $table->date('receive_date');
            $table->unsignedInteger('inv_isu_id');
            $table->foreign('inv_isu_id')->references('id')->on('inv_isus')->onDelete('cascade');

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
        Schema::dropIfExists('inv_finish_fab_rcvs');
    }
}
