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
        Schema::create('tms_settings', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('modul', 50)->nullable();
            $table->string('kode', 50)->nullable();
            $table->jsonb('data')->default("{}");
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tms_settings');
    }
};
