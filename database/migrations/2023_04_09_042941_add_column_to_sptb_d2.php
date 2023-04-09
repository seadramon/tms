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
        Schema::table('sptb_d2', function (Blueprint $table) {
            $table->string('kondisi_produk', 50)->nullable();
        });
        Schema::table('sptb_h', function (Blueprint $table) {
            $table->integer('nilai_pelayanan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sptb_d2', function (Blueprint $table) {
            $table->dropColumn(['kondisi_produk']);
        });
        Schema::table('sptb_h', function (Blueprint $table) {
            $table->dropColumn(['nilai_pelayanan']);
        });
    }
};
