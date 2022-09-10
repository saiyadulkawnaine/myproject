<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',100)->unique();
          $table->string('code',5)->unique();
          $table->string('chief_name',100)->nulable();
          $table->string('address',250)->nulable();
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
        Schema::dropIfExists('sections');
    }
}
