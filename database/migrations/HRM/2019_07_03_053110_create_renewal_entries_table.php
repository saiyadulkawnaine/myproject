<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenewalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_entries', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('renewal_no');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('renewal_item_id');
            $table->foreign('renewal_item_id')->references('id')->on('renewal_items')->onDelete('cascade');
            $table->date('validity_start');
            $table->date('validity_end');
            $table->string('document_no',100);
            $table->unsignedInteger('no_of_sewing_machine')->nullable();
            $table->decimal('fees',14,4); 
            $table->decimal('processing_expense',14,4);   
            $table->string('fees_deposit_to',100);
            $table->date('applied_date')->nullable();
            $table->date('renewed_date')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('renewal_entries');
    }
}
