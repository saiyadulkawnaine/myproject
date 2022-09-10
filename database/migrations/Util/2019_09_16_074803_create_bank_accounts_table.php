<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_branch_id')->unsigned()->index();
            $table->foreign('bank_branch_id')->references('id')->on('bank_branches')->onDelete('cascade');
            $table->string('account_no',50);
            $table->unsignedTinyInteger('account_type_id')->nullable();
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('limit');
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
        Schema::dropIfExists('bank_accounts');
    }
}
