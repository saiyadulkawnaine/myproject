<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoTrimItemQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_trim_item_qties', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('po_trim_item_id');
          $table->foreign('po_trim_item_id')->references('id')->on('po_trim_items')->onDelete('cascade');
		      $table->unsignedInteger('budget_trim_con_id');
          $table->foreign('budget_trim_con_id')->references('id')->on('budget_trim_cons')->onDelete('cascade');
          $table->string('description',250)->nullable();
          $table->decimal('qty',12,4);
		      $table->decimal('rate', 12, 4);
          $table->decimal('amount', 12, 4);
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
        Schema::dropIfExists('po_trim_item_qties');
    }
}
