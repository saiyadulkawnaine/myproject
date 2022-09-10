<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccTransSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_trans_sales', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('acc_trans_prnt_id');
            $table->foreign('acc_trans_prnt_id')->references('id')->on('acc_trans_prnts')->onDelete('cascade');
            $table->unsignedInteger('acc_chart_ctrl_head_id');
            $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads')->onDelete('cascade');
            $table->decimal('exch_rate', 12, 4)->nullable();
            $table->decimal('amount', 14, 4);
            $table->decimal('amount_foreign', 14, 4)->nullable();
            $table->unsignedTinyInteger('treantment_id')->nullable()->default(1);
            $table->unsignedInteger('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('bill_id')->nullable();
            $table->string('bill_no',100)->nullable();
            $table->unsignedTinyInteger('bill_type_id')->nullable();
            $table->unsignedInteger('profitcenter_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('subsection_id')->nullable();
            $table->unsignedTinyInteger('export_lc_id')->nullable();
            $table->string('export_lc_ref_no',100)->nullable();
            $table->string('bank_deposit_slip_no',100)->nullable();
            $table->string('chld_narration',500)->nullable();
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
        Schema::dropIfExists('acc_trans_sales');
    }
}
