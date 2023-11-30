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
        Schema::table('sp3_h', function (Blueprint $table) {
            $table->string('spesifikasi', 250)->nullable();
            $table->jsonb('data')->default('{}');
        });
        Schema::table('sp3_d', function (Blueprint $table) {
            $table->string('port_asal', 250)->nullable();
            $table->string('port_tujuan', 250)->nullable();
            $table->string('site', 250)->nullable();
            $table->string('ritase', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sp3_h', function (Blueprint $table) {
            $table->dropColumn(['jenis_angkutan', 'spesifikasi', 'data']);
        });
        Schema::table('sp3_d', function (Blueprint $table) {
            $table->dropColumn(['port_asal', 'port_tujuan', 'site', 'ritase']);
        });
    }
};
