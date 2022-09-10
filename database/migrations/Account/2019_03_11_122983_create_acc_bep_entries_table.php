<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccBepEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_bep_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('acc_bep_id');
            $table->foreign('acc_bep_id')->references('id')->on('acc_beps')->onDelete('cascade');
            $table->unsignedTinyInteger('expense_type_id')->nullable();
            $table->unsignedTinyInteger('salary_prod_bill_id')->nullable();
            $table->integer('acc_chart_ctrl_head_id')->unsigned();
            $table->foreign('acc_chart_ctrl_head_id')->references('id')->on('acc_chart_ctrl_heads')->onDelete('cascade');
            $table->decimal('amount', 14, 4)->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('acc_bep_entries');
    }
}
