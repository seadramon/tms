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
            $table->foreignId('driver_id')->constrained('tms_drivers')->nullable();
            $table->string('nopol', 50);
            $table->date('tahun')->nullable();
            $table->string('jenis', 100)->nullable();
            $table->string('detail', 100)->nullable();
            $table->string('status', 50)->nullable();
            $table->date('tgl_stnk')->nullable();
            $table->date('foto_stnk')->nullable();
            $table->date('tgl_kir_head')->nullable();
            $table->date('foto_kir_head')->nullable();
            $table->date('tgl_kir_trailer')->nullable();
            $table->date('foto_kir_trailer')->nullable();
            $table->date('tgl_pajak')->nullable();
            $table->date('foto_pajak')->nullable();
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
        Schema::dropIfExists('tms_armadas');
    }
};
