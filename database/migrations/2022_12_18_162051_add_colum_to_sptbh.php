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
        Schema::table('sptb_h', function (Blueprint $table) {
            $table->string('penerima_nama', 200)->nullable();
            $table->string('penerima_ttd', 400)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sptb_h', function (Blueprint $table) {
            $table->dropColumn(['penerima_nama', 'penerima_ttd']);
        });
    }
};
