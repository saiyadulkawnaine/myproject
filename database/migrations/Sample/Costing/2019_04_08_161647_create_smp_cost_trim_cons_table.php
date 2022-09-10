<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmpCostTrimConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smp_cost_trim_cons', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('smp_cost_trim_id');
          $table->foreign('smp_cost_trim_id')->references('id')->on('smp_cost_trims')->onDelete('cascade');
          
          $table->unsignedInteger('style_sample_c_id');
          $table->foreign('style_sample_c_id')->references('id')->on('style_sample_cs')->onDelete('cascade');
          $table->unsignedInteger('trim_color',10)->nullable();
          $table->string('measurment',10)->nullable();
          $table->decimal('cons', 12, 4)->nullable();
          $table->decimal('req_trim', 12, 4)->nullable();
          $table->decimal('process_loss', 12, 4)->nullable();
          $table->decimal('req_cons', 12, 4)->nullable();
          $table->decimal('bom_trim', 12, 4)->nullable();
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
        Schema::dropIfExists('smp_cost_trim_cons');
    }
}
