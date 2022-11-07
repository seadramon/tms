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
        Schema::table('tms_potensi_h', function (Blueprint $table) {
            $table->string('jalan2', 30)->nullable();
            $table->string('jalan_alt2', 30)->nullable();
        });
        Schema::table('spm_h', function (Blueprint $table) {
            $table->string('jalur', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tms_potensi_h', function (Blueprint $table) {
            $table->dropColumn(['jalan2', 'jalan_alt2']);
        });
        Schema::table('spm_h', function (Blueprint $table) {
            $table->dropColumn(['jalur']);
        });
    }
};
