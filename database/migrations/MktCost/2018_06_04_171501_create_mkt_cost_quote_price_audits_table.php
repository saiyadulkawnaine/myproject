<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMktCostQuotePriceAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mkt_cost_quote_price_audits', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('mkt_cost_id')->unsigned();
          $table->foreign('mkt_cost_id')->references('id')->on('mkt_costs')->onDelete('cascade');
          $table->date('qprice_date');
          $table->decimal('quote_price', 12, 4)->nullable();
          $table->date('submission_date');
          $table->date('confirm_date');
          $table->date('refused_date');
          $table->date('cancel_date');
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
        Schema::dropIfExists('mkt_cost_quote_price_audits');
    }
}
