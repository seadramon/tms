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
        Schema::create('tms_armada_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 50);
            $table->string('minggu', 50);
            $table->string('nopol', 50);
            $table->string('driver_name', 50)->nullable();
            $table->string('driver_hp', 50)->nullable();
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
        Schema::dropIfExists('tms_armada_ratings');
    }
};
