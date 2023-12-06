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
        Schema::table('spk_h', function (Blueprint $table) {
            $table->string('kd_jpekerjaan', 10)->nullable();
            $table->string('no_npp', 50)->nullable();
            $table->string('satuan_harsat', 50)->nullable();
            $table->date('jadwal1')->nullable();
            $table->date('jadwal2')->nullable();
            $table->jsonb('data')->default('{}');
            $table->string('pihak1', 255)->nullable();
            $table->string('pihak1_jabatan', 255)->nullable();
            $table->string('pihak2', 255)->nullable();
            $table->string('pihak2_jabatan', 255)->nullable();
            $table->string('spesifikasi', 250)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('spk_d', function (Blueprint $table) {
            $table->string('kd_produk', 14)->nullable();
            $table->string('port_asal', 250)->nullable();
            $table->string('port_tujuan', 250)->nullable();
            $table->integer('jarak')->nullable();
            $table->integer('vol_btg')->nullable();
            $table->integer('vol_ton')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->integer('ritase')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spk_h', function (Blueprint $table) {
            $table->dropColumn(['kd_jpekerjaan', 'no_npp', 'satuan_harsat', 'jadwal1', 'jadwal2', 'data', 'pihak1', 'pihak1_jabatan', 'pihak2', 'pihak2_jabatan', 'spesifikasi', 'created_at', 'updated_at', 'deleted_at']);
        });
        Schema::table('spk_d', function (Blueprint $table) {
            $table->dropColumn(['kd_produk', 'port_asal', 'port_tujuan', 'jarak', 'vol_btg', 'vol_ton', 'satuan', 'ritase']);
        });
    }
};
