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
            $table->softDeletes();
            $table->string('source_lat', 50)->nullable();
            $table->string('source_long', 50)->nullable();
            $table->string('dest_lat', 50)->nullable();
            $table->string('dest_long', 50)->nullable();
            $table->text('checkpoints')->nullable();
            $table->text('rute')->nullable();
            $table->string('jalan', 30)->nullable();
            $table->string('jembatan', 30)->nullable();
            $table->string('jalan_alt', 30)->nullable();
            $table->string('langsir', 30)->nullable();
            $table->string('jarak_langsir', 30)->nullable();
            $table->string('metode', 30)->nullable();
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
            $table->dropColumn(['deleted_at', 'source_lat', 'source_long', 'dest_lat', 'dest_long', 'source_lat', 'checkpoints', 'rute', 'jalan', 'jembatan', 'jalan_alt', 'langsir', 'jarak_langsir', 'metode']);
        });
    }
};
