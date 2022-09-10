<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanySubsectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_subsections', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->integer('company_id')->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('subsection_id')->unsigned()->index();
            $table->foreign('subsection_id')->references('id')->on('subsections')->onDelete('cascade');
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
        Schema::dropIfExists('company_subsections');
    }
}
