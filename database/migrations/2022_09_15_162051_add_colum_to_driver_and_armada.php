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
        Schema::table('tms_drivers', function (Blueprint $table) {
            $table->string('sim_no', 50)->nullable();
            $table->string('status', 20)->nullable();
        });
        Schema::table('tms_armadas', function (Blueprint $table) {
            $table->string('v_stnk', 50)->nullable();
            $table->string('v_kir_head', 50)->nullable();
            $table->string('v_kir_trailer', 50)->nullable();
            $table->string('v_pajak', 50)->nullable();
            $table->string('v_status', 50)->nullable();
            $table->string('visual', 50)->nullable();
            $table->string('kelengkapan', 50)->nullable();
            $table->string('kondisi_ban', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tms_drivers', function (Blueprint $table) {
            $table->dropColumn(['sim_no', 'status']);
        });
        Schema::table('tms_armadas', function (Blueprint $table) {
            $table->dropColumn(['v_stnk', 'v_kir_head', 'v_kir_trailer', 'v_pajak', 'v_status', 'visual', 'kelengkapan', 'kondisi_ban']);
        });
    }
};
