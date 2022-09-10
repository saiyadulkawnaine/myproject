<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsections', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',100)->unique();
          $table->string('code',5)->unique();
          $table->unsignedInteger('employee_id')->nulable()->unsigned();//chief name
          $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
          $table->string('address',250)->nulable();
          $table->unsignedInteger('floor_id')->unsigned();
          $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
          $table->unsignedTinyInteger('is_treat_sewing_line')->nulable();
          $table->unsignedTinyInteger('is_poly_layout_id')->nulable();
          $table->unsignedTinyInteger('projected_line_id')->nulable();  
          $table->unsignedInteger('qty')->nulable();
          $table->decimal('amount',14,4)->nulable();
          $table->unsignedInteger('uom_id')->nulable()->unsigned();
          $table->foreign('uom_id')->references('id')->on('uoms')->onDelete('cascade');
          $table->unsignedTinyInteger('gmt_complexity_id')->nulable();
          $table->unsignedTinyInteger('status_id')->nulable();
          $table->unsignedInteger('no_of_operator')->nulable();
          $table->unsignedInteger('no_of_helper')->nulable();
          $table->unsignedSmallInteger('prod_source_id')->nullable();
          $table->unsignedSmallInteger('sort_id')->nulable();
          $table->unsignedSmallInteger('created_by')->nulable();
          $table->timestamp('created_at')->nulable();
          $table->unsignedSmallInteger('updated_by')->nulable();
          $table->timestamp('updated_at')->nulable();
          $table->timestamp('deleted_at')->nulable();
          $table->string('created_ip',20)->nulable();
          $table->string('updated_ip',20)->nulable();
          $table->string('deleted_ip',20)->nulable();
          $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subsections');
    }
}
