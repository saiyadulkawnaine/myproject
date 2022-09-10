<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpProRlzDeductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_pro_rlz_deducts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exp_pro_rlz_id')->unsigned();
            $table->foreign('exp_pro_rlz_id')->references('id')->on('exp_pro_rlzs')->onDelete('cascade');
            $table->integer('commercial_head_id')->unsigned();
            $table->foreign('commercial_head_id')->references('id')->on('commercial_heads')->onDelete('cascade');
            $table->decimal('doc_value',14,4)->nullable();
            $table->decimal('exch_rate', 12, 4);
            $table->decimal('dom_value',14,4);
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
        Schema::dropIfExists('exp_pro_rlz_deducts');
    }
}
