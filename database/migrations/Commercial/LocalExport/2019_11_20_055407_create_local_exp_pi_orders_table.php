<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalExpPiOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_exp_pi_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('local_exp_pi_id');
            $table->foreign('local_exp_pi_id')->references('id')->on('local_exp_pis')->onDelete('cascade');
            $table->unsignedInteger('sales_order_ref_id');
            $table->unsignedInteger('qty')->nullable();
            //$table->decimal('rate', 12, 4)->nullable();
            $table->decimal('amount', 14, 4)->nullable();
            $table->decimal('discount_per', 10, 6)->nullable();
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
        Schema::dropIfExists('local_exp_pi_orders');
    }
}
