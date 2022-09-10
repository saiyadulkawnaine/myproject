<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmpCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smp_costs', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id');
          $table->unsignedInteger('style_sample_id')->unsigned();
          $table->foreign('style_sample_id')->references('id')->on('style_samples')->onDelete('cascade');
          $table->unsignedTinyInteger('costing_unit_id');
          $table->unsignedInteger('currency_id');
          $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
          $table->decimal('exchange_rate', 12, 4)->nullable();
          $table->string('remarks',500)->nullable();
          $table->date('costing_date')->nullable();

          $table->unsignedSmallInteger('first_approved_by')->nullable();
          $table->timestamp('first_approved_at')->nullable();
          $table->unsignedSmallInteger('second_approved_by')->nullable();
          $table->timestamp('second_approved_at')->nullable();
          $table->unsignedSmallInteger('third_approved_by')->nullable();
          $table->timestamp('third_approved_at')->nullable();
          $table->unsignedSmallInteger('final_approved_by')->nullable();
          $table->timestamp('final_approved_at')->nullable();

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
        Schema::dropIfExists('smp_costs');
    }
}
