<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_periods', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('acc_year_id')->unsigned();
            $table->foreign('acc_year_id')->references('id')->on('acc_years')->onDelete('cascade');
            $table->unsignedTinyInteger('period');
            $table->unsignedTinyInteger('is_open')->nullable()->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('name',20);
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
        Schema::dropIfExists('acc_periods');
    }
}
