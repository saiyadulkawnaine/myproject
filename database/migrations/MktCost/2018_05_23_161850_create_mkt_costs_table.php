<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMktCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mkt_costs', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id');
          $table->unsignedInteger('style_id')->unique();
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
          $table->unsignedTinyInteger('costing_unit_id');
          $table->date('quot_date');
          $table->unsignedInteger('currency_id');
          $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
          $table->unsignedSmallInteger('incoterm_id');
          $table->string('incoterm_place',100);
          $table->decimal('sewing_smv', 12, 4)->nullable();
          $table->decimal('sewing_effi_per', 12, 4)->nullable();
          $table->decimal('exchange_rate', 12, 4)->nullable();
          $table->unsignedInteger('offer_qty');
          $table->unsignedInteger('uom_id')->unsigned()->nullable();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
          $table->date('est_ship_date');
          $table->date('op_date');
          $table->unsignedSmallInteger('lead_time')->nullable();
          $table->unsignedSmallInteger('week_no')->nullable();
          $table->unsignedInteger('team_id')->unsigned()->nullable();
          $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
          $table->date('confirm_date')->nullable();
          $table->string('remarks',500)->nullable();
		  $table->decimal('quote_price', 12, 4)->nullable();
          $table->decimal('target_price', 12, 4)->nullable();

          $table->unsignedInteger('production_per_hr')->nullable();
          // $table->unsignedInteger('no_of_manpowar')->nullable();
          $table->unsignedInteger('buyer_development_order_qty_id')->nullable();
          $table->unsignedSmallInteger('confirmed_by')->nullable();
          $table->timestamp('confirmed_at')->nullable();
          
          $table->unsignedSmallInteger('first_approved_by')->nullable();
          $table->timestamp('first_approved_at')->nullable();
          $table->unsignedSmallInteger('second_approved_by')->nullable();
          $table->timestamp('second_approved_at')->nullable();
          $table->unsignedSmallInteger('third_approved_by')->nullable();
          $table->timestamp('third_approved_at')->nullable();
          $table->unsignedSmallInteger('final_approved_by')->nullable();
          $table->timestamp('final_approved_at')->nullable();

          $table->unsignedSmallInteger('returned_by')->nullable();
          $table->timestamp('returned_at')->nullable();
          $table->string('returned_coments',2000)->nullable();

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
        Schema::dropIfExists('mkt_costs');
    }
}
