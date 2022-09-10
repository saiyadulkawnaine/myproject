<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoDyeChemItemQtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_dye_chem_item_qties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('po_dye_chem_item_id');
            $table->foreign('po_dye_chem_item_id')->references('id')->on('po_dye_chem_items')->onDelete('cascade');
            $table->decimal('qty',12,4);
            $table->decimal('rate', 12, 4);
            $table->decimal('amount', 12, 4);
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
        Schema::dropIfExists('po_dye_chem_item_qties');
    }
}
