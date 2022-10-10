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
        Schema::create('tms_potensi_h', function (Blueprint $table) {
            $table->id();
            $table->string('no_npp', 20);
            $table->string('kd_material', 20)->nullable();
            $table->string('jenis_armada', 100)->nullable();
            $table->string('pat_to', 20)->nullable();
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
        Schema::dropIfExists('tms_potensi_h');
    }
};
