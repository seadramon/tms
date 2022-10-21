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
        Schema::create('tms_pelanggan_users', function (Blueprint $table) {
            $table->id();
            $table->string('pelanggan_id', 30);
            $table->foreign('pelanggan_id')->references('pelanggan_id')->on('pelanggan');
            $table->string('nama', 200)->nullable();
            $table->string('ktp', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('jabatan', 20)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('foto_path', 200)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('tms_pelanggan_users');
    }
};
