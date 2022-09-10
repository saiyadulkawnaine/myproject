<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoYarnDyeingItemBomQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_yarn_dyeing_item_bom_qties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('po_yarn_dyeing_item_id');
            $table->foreign('po_yarn_dyeing_item_id','poyarndyeingitemid')->references('id')->on('po_yarn_dyeing_items')->onDelete('cascade');
            $table->unsignedInteger('budget_yarn_dyeing_con_id');
            $table->foreign('budget_yarn_dyeing_con_id','budgetyarndyeingconid')->references('id')->on('budget_yarn_dyeing_cons');

            $table->unsignedInteger('colorrange_id')->nullable();
            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('process_loss_per',12,4)->nullable();
            $table->unsignedInteger('req_cone')->nullable();
            $table->decimal('wgt_per_cone', 14, 4)->nullable();
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('po_yarn_dyeing_item_bom_qties');
    }
}
