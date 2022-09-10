<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('style_samples', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('style_id')->unsigned();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedInteger('style_gmt_id')->unsigned();
          $table->foreign('style_gmt_id')->references('id')->on('style_gmts')->onDelete('cascade');
          $table->unsignedInteger('gmtssample_id')->unsigned();
          $table->foreign('gmtssample_id')->references('id')->on('gmtssamples')->onDelete('cascade');
          $table->unsignedTinyInteger('approval_priority')->nullable();
          $table->unsignedSmallInteger('sort_id');
          $table->unsignedTinyInteger('is_charge_allowed')->nullable();
          $table->unsignedTinyInteger('is_costing_allowed')->nullable();
          $table->unsignedInteger('currency_id')->unsigned();
          $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
          $table->decimal('rate', 8, 4)->nullable();
          $table->unsignedSmallInteger('fabric_instruction_id')->nullable();
          $table->unsignedInteger('qty')->nullable();
          $table->string('remarks')->nullable();
          $table->date('sub_from')->nullable();
          $table->date('sub_to')->nullable();
          $table->date('app_from')->nullable();
          $table->date('app_to')->nullable();
          $table->date('pattern_from')->nullable();
          $table->date('pattern_to')->nullable();
          $table->date('sample_booking_from')->nullable();
          $table->date('sample_booking_to')->nullable();
          $table->date('yarn_inhouse_from')->nullable();
          $table->date('yarn_inhouse_to')->nullable();
          $table->date('yarn_dyeing_from')->nullable();          
          $table->date('yarn_dyeing_to')->nullable();
          $table->date('knitting_from')->nullable();          
          $table->date('knitting_to')->nullable();
          $table->date('dyeing_from')->nullable();          
          $table->date('dyeing_to')->nullable();
          $table->date('aop_from')->nullable();          
          $table->date('aop_to')->nullable();
          $table->date('finishing_from')->nullable();          
          $table->date('finishing_to')->nullable();
          $table->date('cutting_from')->nullable();          
          $table->date('cutting_to')->nullable();
          $table->date('print_emb_from')->nullable();          
          $table->date('print_emb_to')->nullable();
          $table->date('emb_from')->nullable();          
          $table->date('emb_to')->nullable();
          $table->date('washing_from')->nullable();          
          $table->date('washing_to')->nullable();
          $table->date('trims_from')->nullable();          
          $table->date('trims_to')->nullable();
          $table->date('sewing_from')->nullable();          
          $table->date('sewing_to')->nullable();
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
        Schema::dropIfExists('style_samples');
    }
}
