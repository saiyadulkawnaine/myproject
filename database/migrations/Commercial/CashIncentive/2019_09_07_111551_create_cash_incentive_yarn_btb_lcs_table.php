<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashIncentiveYarnBtbLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_incentive_yarn_btb_lcs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cash_incentive_ref_id')->unsigned();       
            $table->foreign('cash_incentive_ref_id')->references('id')->on('cash_incentive_refs')->onDelete('cascade');
            $table->integer('imp_lc_id')->unsigned();
            $table->foreign('imp_lc_id')->references('id')->on('imp_lcs')->onDelete('cascade');//btb_lc_no

            /* $table->date('lc_date');
            $table->unsignedInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            
            $table->decimal('lc_yarn_qty',14,4);
            $table->decimal('rate',10,6);
            $table->decimal('lc_yarn_amount',14,4);
            $table->unsignedInteger('item_account_id')->unsigned();
            $table->foreign('item_account_id')->references('id')->on('item_accounts')->onDelete('cascade'); */

            $table->unsignedInteger('po_yarn_item_id');
            $table->foreign('po_yarn_item_id')->references('id')->on('po_yarn_items')->onDelete('cascade');
            $table->decimal('consumed_qty',14,4);
            $table->decimal('comsumed_amount',14,4);
            //$table->decimal('prev_used_qty',14,4);
            //$table->decimal('balance_qty',14,4);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('cash_incentive_yarn_btb_lcs');
    }
}
