<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvYarnRcvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_yarn_rcvs', function (Blueprint $table) {
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
            $table->string('challan_no');
            $table->date('receive_date');

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
        Schema::dropIfExists('inv_yarn_rcvs');
    }
}
