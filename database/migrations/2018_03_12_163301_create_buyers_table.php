<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->string('name',100)->unique();
          $table->string('code',10)->unique();
          $table->string('vendor_code',25)->nullable();
          $table->unsignedInteger('supplier_id')->nullable();
          $table->decimal('sew_effin_percent', 8, 4)->nullable();
          $table->unsignedInteger('team_id')->nullable();
          $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
          $table->unsignedInteger('teammember_id')->nullable();
          $table->foreign('teammember_id')->references('id')->on('teammembers')->onDelete('cascade');
          $table->unsignedTinyInteger('is_subcon_delv_secured')->nullable();
          $table->decimal('cr_limit_amt', 12, 4)->nullable();
          $table->unsignedSmallInteger('cr_limit_Day')->nullable();
          $table->unsignedTinyInteger('discount_method_Id')->nullable();
          $table->unsignedTinyInteger('is_sec_deduction')->nullable();
          $table->unsignedTinyInteger('is_vat_deduction')->nullable();
          $table->unsignedTinyInteger('is_ait_deduction')->nullable();
          $table->unsignedInteger('buying_agent_id')->nullable();
          $table->unsignedInteger('company_id')->nullable();
          $table->string('contact_person',100)->nullable();
          $table->string('designation',100)->nullable();
          $table->string('email',100)->nullable();
          $table->string('cell_no',20)->nullable();
          $table->string('address',250)->nullable();
          
          $table->unsignedInteger('status_id')->nullable();
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
        Schema::dropIfExists('buyers');
    }
}
