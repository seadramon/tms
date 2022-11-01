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
        Schema::create('tms_pelanggan_npps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_user_id')->constrained('tms_pelanggan_users');
            $table->string('no_npp', 30);
            $table->foreign('no_npp')->references('no_npp')->on('npp');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('tms_pelanggan_users', function (Blueprint $table) {
            $table->string('status', 50)->default('new');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tms_pelanggan_npps');
        Schema::table('tms_pelanggan_users', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
};
