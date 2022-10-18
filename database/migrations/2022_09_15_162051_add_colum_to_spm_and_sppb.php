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
        Schema::table('spm_h', function (Blueprint $table) {
            $table->string('pat_to', 50)->nullable();
            $table->string('app2_hp', 50)->nullable();
        });
        Schema::table('sppb_h', function (Blueprint $table) {
            $table->string('no_npp', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spm_h', function (Blueprint $table) {
            $table->dropColumn(['app2_hp', 'pat_to']);
        });
        Schema::table('sppb_h', function (Blueprint $table) {
            $table->dropColumn(['no_npp']);
        });
    }
};
