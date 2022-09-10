<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeBudgetPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_budget_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('employee_budget_id')->unsigned();
            $table->foreign('employee_budget_id')->references('id')->on('employee_budgets')->onDelete('cascade');
            $table->unsignedInteger('designation_id');
            $table->foreign('designation_id','desId')->references('id')->on('designations')->onDelete('cascade');
            $table->unsignedInteger('grade')->nullable();
            $table->unsignedInteger('no_of_post');
            $table->date('date_of_join');
            $table->decimal('max_salary',14,4)->nullable();
            $table->decimal('min_salary',14,4)->nullable();
            $table->string('last_education',400)->nullable();
            $table->string('professional_education',400)->nullable();
            $table->string('special_qualificaiton',400)->nullable();
            $table->string('experience',400)->nullable();
            $table->unsignedSmallInteger('room_required_id')->nullable();
            $table->unsignedSmallInteger('desk_required_id')->nullable();
            $table->unsignedSmallInteger('intercom_required_id')->nullable();
            $table->unsignedSmallInteger('computer_required_id')->nullable();
            $table->unsignedSmallInteger('ups_required_id')->nullable();
            $table->unsignedSmallInteger('printer_required_id')->nullable();
            $table->unsignedSmallInteger('cell_phone_required_id')->nullable();
            $table->unsignedSmallInteger('sim_required_id')->nullable();
            $table->unsignedSmallInteger('network_required_id')->nullable();
            $table->unsignedSmallInteger('transport_required_id')->nullable();
            $table->string('other_item_required',500)->nullable();
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
        Schema::dropIfExists('employee_budget_positions');
    }
}
