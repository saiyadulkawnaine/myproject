<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id')->unsigned();
      			$table->string('name')->unique();
            $table->string('router')->nullable();
      			$table->unsignedInteger('root_id')->nullable();
      			$table->unsignedSmallInteger('sort_id')->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
      			$table->timestamp('created_at')->nullable();
      			$table->unsignedSmallInteger('updated_by')->nullable();
      			$table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip',20)->nullable();
            $table->string('updated_ip',20)->nullable();
            $table->string('deleted_ip',20)->nullable();
      			$table->softDeletes();
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
        Schema::dropIfExists('menus');
    }
}
