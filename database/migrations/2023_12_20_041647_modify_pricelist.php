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
        Schema::table('tms_pricelist_angkutan_h', function (Blueprint $table) {
            $table->string('jenis', 50)->default('darat');
        });
        Schema::table('tms_pricelist_angkutan_d2', function (Blueprint $table) {
            $table->string('kondisi', 10)->nullable();
            $table->string('unit', 10)->nullable();
            $table->string('site', 250)->nullable();
            $table->string('satuan', 250)->nullable();
            $table->string('port_asal', 250)->nullable();
            $table->string('port_tujuan', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tms_pricelist_angkutan_h', function (Blueprint $table) {
            $table->dropColumn(['jenis']);
        });
        Schema::table('tms_pricelist_angkutan_d2', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'unit', 'site', 'satuan', 'port_asal', 'port_tujuan']);
        });
    }
};
