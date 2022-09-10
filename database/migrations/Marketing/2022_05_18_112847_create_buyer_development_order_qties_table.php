<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyerDevelopmentOrderQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_development_order_qties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_development_order_id');
            $table->foreign("buyer_development_order_id","buyerdevelopmentref")->references("id")->on("buyer_development_orders")->onDelete("cascade");
            $table->unsignedInteger("qty");
            $table->decimal("rate",10,4);
            $table->decimal("amount",14,4);
            $table->unsignedInteger("rcv_qty")->nullable();
            $table->decimal("rcv_rate",10,4)->nullable();
            $table->decimal("rcv_amount",14,4)->nullable();
            $table->date("est_ship_date");

            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('buyer_development_order_qties');
    }
}
