<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTnatasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tnatasks', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedSmallInteger('tna_task_id');
          $table->string('task_name',250);
          $table->unsignedTinyInteger('is_auto_completion');
          $table->decimal('completion_treated_percent', 5, 2)->nullable();
          $table->unsignedTinyInteger('penalty_applicable_id')->nullable();
          $table->decimal('panalty_amount', 8, 4)->nullable();
          $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('tnatasks');
    }
}
