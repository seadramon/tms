<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tms_pricelist_angkutan_d2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_id')->nullable()->constrained('tms_pricelist_angkutan_d');
            $table->integer('range_min')->nullable();
            $table->integer('range_max')->nullable();
            $table->decimal('h_pusat', 12, 2)->nullable();
            $table->decimal('h_final', 12, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tms_pricelist_angkutan_d2');
    }
};
