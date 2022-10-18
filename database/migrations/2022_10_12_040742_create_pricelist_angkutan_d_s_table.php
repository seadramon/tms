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
        Schema::create('tms_pricelist_angkutan_d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pah_id')->nullable()->constrained('tms_pricelist_angkutan_h');
            $table->string('kd_material', 30)->nullable();
            $table->string('jenis_muat', 50)->nullable();
            $table->string('kd_muat', 50)->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
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
        Schema::dropIfExists('tms_pricelist_angkutan_d');
    }
};
