<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDyeingServiceItemRespsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_dyeing_service_item_resps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('po_dyeing_service_item_id');
            $table->foreign('po_dyeing_service_item_id')->references('id')->on('po_dyeing_service_items')->onDelete('cascade');
            $table->unsignedInteger('employee_h_r_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('short_type_id');
            $table->decimal('cost_share_per', 12, 4);
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('po_dyeing_service_item_resps');
    }
}
