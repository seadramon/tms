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
        Schema::create('tms_armadas', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_id', 30);
            $table->foreignId('driver_id')->nullable()->constrained('tms_drivers');
            $table->string('nopol', 50);
            $table->string('tahun', 8)->nullable();
            $table->string('kd_armada', 20)->nullable();
            $table->string('detail', 200)->nullable();
            $table->string('status', 50)->nullable();
            $table->date('tgl_stnk')->nullable();
            $table->string('foto_stnk', 200)->nullable();
            $table->date('tgl_kir_head')->nullable();
            $table->string('foto_kir_head', 200)->nullable();
            $table->date('tgl_kir_trailer')->nullable();
            $table->string('foto_kir_trailer', 200)->nullable();
            $table->date('tgl_pajak')->nullable();
            $table->string('foto_pajak', 200)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('kd_armada')->references('kd_material')->on('tr_material');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tms_armadas');
    }
};
