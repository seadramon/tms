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
        Schema::create('tms_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id', 30);
            $table->string('nama', 50)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_hp', 50)->nullable();
            $table->string('sim_jenis', 10)->nullable();
            $table->string('sim_path', 255)->nullable();
            $table->date('sim_expired')->nullable();
            $table->date('tgl_bergabung')->nullable();
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
        Schema::dropIfExists('tms_drivers');
    }
};
