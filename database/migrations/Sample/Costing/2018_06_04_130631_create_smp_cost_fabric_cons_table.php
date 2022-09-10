<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmpCostFabricConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smp_cost_fabric_cons', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('smp_cost_fabric_id');
          $table->foreign('smp_cost_fabric_id')->references('id')->on('smp_cost_fabrics')->onDelete('cascade');
          $table->unsignedInteger('style_sample_c_id');
          $table->foreign('style_sample_c_id')->references('id')->on('style_sample_cs')->onDelete('cascade');
          $table->decimal('body_lenght', 12, 4)->nullable();
          $table->decimal('body_sewing_margin', 12, 4)->nullable();
          $table->decimal('body_hem_margin', 12, 4)->nullable();
          $table->decimal('sleeve_lenght', 12, 4)->nullable();
          $table->decimal('sleeve_sewing_margin', 12, 4)->nullable();
          $table->decimal('sleeve_hem_margin', 12, 4)->nullable();
          $table->decimal('chest_lenght', 12, 4)->nullable();
          $table->decimal('chest_sewing_margin', 12, 4)->nullable();
          $table->decimal('frontraise_lenght', 12, 4)->nullable();
          $table->decimal('frontraise_sewing_margin', 12, 4)->nullable();
          $table->decimal('westband_lenght', 12, 4)->nullable();
          $table->decimal('westband_sewing_margin', 12, 4)->nullable();
          $table->decimal('inseam_lenght', 12, 4)->nullable();
          $table->decimal('inseam_sewing_margin', 12, 4)->nullable();
          $table->decimal('inseam_hem_margin', 12, 4)->nullable();
          $table->decimal('thai_lenght', 12, 4)->nullable();
          $table->decimal('thai_sewing_margin', 12, 4)->nullable();
          $table->string('dia',100)->nullable();
          $table->unsignedInteger('fabric_color')->nullable();
          $table->decimal('cons', 12, 4)->nullable();
          $table->decimal('fin_fab', 12, 4)->nullable();
          $table->decimal('process_loss', 12, 4)->nullable();
          $table->decimal('req_cons', 12, 4)->nullable();
          $table->decimal('grey_fab', 12, 4)->nullable();
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
        Schema::dropIfExists('smp_cost_fabric_cons');
    }
}
