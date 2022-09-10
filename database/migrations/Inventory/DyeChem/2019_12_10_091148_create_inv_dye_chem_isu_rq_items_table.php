<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvDyeChemIsuRqItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dye_chem_isu_rq_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_dye_chem_isu_rq_id');
            $table->foreign('inv_dye_chem_isu_rq_id')->references('id')->on('inv_dye_chem_isu_rqs')->onDelete('cascade');

            $table->unsignedInteger('sub_process_id');
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->decimal('per_on_batch_wgt',14,4);
            $table->decimal('gram_per_ltr_liqure',14,4);
            $table->decimal('rto_on_paste_wgt',14,4);
            $table->unsignedInteger('print_type_id');
            $table->unsignedInteger('so_aop_id');
            $table->foreign('so_aop_id','soaopiddyechemisurq')->references('id')->on('so_aops');
            $table->unsignedInteger('so_emb_id');
            $table->foreign('so_emb_id','soembiddyechemisurq')->references('id')->on('so_embs');
            $table->unsignedInteger('asset_quantity_cost_id')->nulable();
            $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs');
            $table->decimal('qty',14,4);
            $table->decimal('first_qty',14,4);
            $table->decimal('add_per',14,4);
            $table->unsignedInteger('root_item_id');
            $table->unsignedSmallInteger('sort_id');
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
        Schema::dropIfExists('inv_dye_chem_isu_rq_items');
    }
}
