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
        Schema::table('bapp_d1', function (Blueprint $table) {
            $table->string('vendor_id', 50)->nullable();
            $table->string('pihak1', 50)->nullable();
            $table->string('pihak2', 255)->nullable();
            $table->string('pihak2_jabatan', 255)->nullable();
        });
        Schema::table('bapp_d1', function (Blueprint $table) {
            $table->decimal('sp3_vol_btg', 12, 2)->nullable();
            $table->decimal('sp3_vol_ton', 12, 2)->nullable();
            $table->decimal('lalu_vol_btg', 12, 2)->nullable();
            $table->decimal('lalu_vol_ton', 12, 2)->nullable();
            $table->decimal('vol_btg', 12, 2)->nullable();
            $table->decimal('vol_ton', 12, 2)->nullable();
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
        Schema::table('bapp_h1', function (Blueprint $table) {
            $table->dropColumn(['vendor_id', 'pihak1', 'pihak2', 'pihak2_jabatan']);
        });
        Schema::table('bapp_d1', function (Blueprint $table) {
            $table->dropColumn(['sp3_vol_btg', 'sp3_vol_ton', 'lalu_vol_btg', 'lalu_vol_ton', 'vol_btg', 'vol_ton', 'created_at', 'updated_at']);
        });
    }
};
