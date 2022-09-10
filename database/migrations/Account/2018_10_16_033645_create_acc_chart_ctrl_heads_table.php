<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccChartCtrlHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_chart_ctrl_heads', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('acc_chart_sub_group_id');
            $table->foreign('acc_chart_sub_group_id')->references('id')->on('acc_chart_sub_groups')->onDelete('cascade');
            $table->unsignedInteger('code')->unique();
            $table->string('name',500);
            $table->unsignedInteger('root_id')->nullable();
            $table->unsignedTinyInteger('ctrlhead_type_id');
            $table->unsignedSmallInteger('statement_type_id')->nullable();
            $table->unsignedInteger('retained_earning_account_id')->nullable();
            $table->unsignedSmallInteger('control_name_id')->nullable();
            $table->unsignedSmallInteger('other_type_id')->nullable()->default(0);
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedTinyInteger('normal_balance_id')->nullable();
            $table->unsignedTinyInteger('is_cm_expense')->nullable();
			$table->unsignedTinyInteger('expense_type_id')->nullable();
            
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
        Schema::dropIfExists('acc_chart_ctrl_heads');
    }
}
