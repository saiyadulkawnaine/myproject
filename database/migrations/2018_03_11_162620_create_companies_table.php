<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('companies', function (Blueprint $table) {
   $table->increments('id')->unsignedInteger();
   $table->unsignedInteger('cgroup_id')->unsigned();
   $table->foreign('cgroup_id')->references('id')->on('cgroups')->onDelete('cascade');
   $table->string('name', 100);
   $table->string('code', 5);
   $table->string('ceo', 100)->nullable();
   $table->string('email', 100)->nullable();
   $table->string('post_code', 100)->nullable();
   $table->unsignedSmallInteger('sort_id');
   $table->unsignedSmallInteger('nature_id');
   $table->unsignedSmallInteger('legal_status_id');
   $table->string('trade_license_no', 50)->nullable();
   $table->string('incorporation_no', 50)->nullable();
   $table->string('erc_no', 50)->nullable();
   $table->string('irc_no', 50)->nullable();
   $table->string('epb_reg_no', 50)->nullable();
   $table->date('trade_lic_renew_date')->nullable();
   $table->date('erc_expiry_date')->nullable();
   $table->date('irc_expiry_date')->nullable();
   $table->string('tin_number', 50)->nullable();
   $table->string('vat_number', 50)->nullable();
   $table->string('ban_bank_reg_no', 50)->nullable();
   $table->date('ban_bank_reg_date')->nullable();
   $table->decimal('cut_panel_reject_per', 8, 4)->nullable();
   $table->decimal('gmt_reject_per', 8, 4)->nullable();
   $table->decimal('gmt_alter_per', 8, 4)->nullable();
   $table->unsignedTinyInteger('man_machine_ratio')->nullable();
   $table->decimal('earning_per_minute', 8, 4)->nullable();
   $table->decimal('earning_per_machine', 8, 4)->nullable();
   $table->decimal('earning_per_employee', 8, 4)->nullable();
   $table->decimal('knit_process_loss_per', 8, 4)->nullable();
   $table->decimal('dye_process_loss_per', 8, 4)->nullable();
   $table->decimal('on_time_delv_per', 8, 4)->nullable();
   $table->decimal('sew_effic_per', 8, 4)->nullable();
   $table->decimal('order_to_ship_per', 8, 4)->nullable();
   $table->decimal('cut_to_ship_per', 8, 4)->nullable();
   $table->decimal('first_pass_yield', 8, 4)->nullable();
   $table->decimal('margin_of_erosion', 8, 4)->nullable();
   $table->unsignedTinyInteger('acc_code_length')->nullable();
   $table->unsignedTinyInteger('post_in_previous_yr')->nullable();
   $table->unsignedTinyInteger('auto_service_cost_alloca_id')->nullable();
   $table->unsignedTinyInteger('profit_center')->nullable();
   $table->unsignedTinyInteger('book_keeping_currency')->nullable();
   $table->string('address', 250)->nullable();
   $table->string('contact')->nullable();
   $table->string('logo', 100)->nullable();
   $table->string('rex_no', 400)->nullable();
   $table->date('rex_date')->nullable();
   $table->decimal('knitting_capacity_qty')->nullable();
   $table->decimal('knitting_capacity_amount', 14, 4)->nullable();
   $table->decimal('dyeing_capacity_qty')->nullable();
   $table->decimal('dyeing_capacity_amount', 14, 4)->nullable();
   $table->decimal('fabric_finish_capacity_qty')->nullable();
   $table->decimal('fabric_finish_capacity_amount', 14, 4)->nullable();
   $table->decimal('aop_capacity_qty')->nullable();
   $table->decimal('aop_capacity_amount', 14, 4)->nullable();
   $table->decimal('screen_print_capacity_qty')->nullable();
   $table->decimal('screen_print_capacity_amount', 14, 4)->nullable();
   $table->decimal('embroidery_capacity_qty')->nullable();
   $table->decimal('embroidery_capacity_amount', 14, 4)->nullable();
   $table->decimal('cutting_capacity_qty')->nullable();
   $table->decimal('cutting_capacity_amount', 14, 4)->nullable();
   $table->decimal('poly_capacity_qty')->nullable();
   $table->decimal('poly_capacity_amount', 14, 4)->nullable();
   $table->decimal('iron_capacity_qty')->nullable();
   $table->decimal('iron_capacity_amount', 14, 4)->nullable();
   $table->decimal('cartoning_capacity_qty')->nullable();
   $table->decimal('cartoning_capacity_amount', 14, 4)->nullable();
   $table->unsignedSmallInteger('created_by')->nullable();
   $table->timestamp('created_at')->nullable();
   $table->unsignedSmallInteger('updated_by')->nullable();
   $table->timestamp('updated_at')->nullable();
   $table->timestamp('deleted_at')->nullable();
   $table->string('created_ip', 20)->nullable();
   $table->string('updated_ip', 20)->nullable();
   $table->string('deleted_ip', 20)->nullable();
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
  Schema::dropIfExists('companies');
 }
}
