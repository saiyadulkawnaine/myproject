<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightMachineUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_machine_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weight_machine_id')->unsigned()->index();
            $table->foreign('weight_machine_id')->references('id')->on('weight_machines')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('weight_machine_users');
    }
}
