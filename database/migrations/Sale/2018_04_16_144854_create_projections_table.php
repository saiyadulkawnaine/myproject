<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projections', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('company_id');
          $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
		  $table->unsignedInteger('style_id');
          $table->foreign('style_id')->references('id')->on('styles')->onDelete('cascade');
		  $table->string('proj_no',100);
          $table->date('date');
		  $table->unsignedInteger('currency_id');
          $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
          $table->decimal('exch_rate', 8, 4);
		  $table->string('file_no',100);
          $table->string('remarks',255)->nullable();
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
        Schema::dropIfExists('projections');
    }
}
