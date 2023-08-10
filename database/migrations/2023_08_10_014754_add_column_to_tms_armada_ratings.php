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
        Schema::table('tms_armada_ratings', function (Blueprint $table) {
            $table->string('no_spm', 100)->nullable();
            $table->string('blth', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tms_armada_ratings', function (Blueprint $table) {
            $table->dropColumn(['no_spm', 'blth']);
        });
    }
};
