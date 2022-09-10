<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->string('name',100);
            $table->string('code',5);
            $table->unsignedTinyInteger('region_Id')->nullable();
            $table->unsignedTinyInteger('economy_level_Id')->nullable();
            $table->unsignedInteger('population')->nullable();
            $table->decimal('female_percent', 8, 4)->nullable();
            $table->decimal('adult_percent', 8, 4)->nullable();
            $table->decimal('teenage_percent', 8, 4)->nullable();
            $table->unsignedTinyInteger('political_stability_Id')->nullable();
            $table->unsignedTinyInteger('cut_off_Id')->nullable();
            $table->unsignedSmallInteger('sort_id');
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
        Schema::dropIfExists('countries');
    }
}
