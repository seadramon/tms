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
        Schema::table('sppb_h', function (Blueprint $table) {
            $table->string('app2_jbt', 30)->nullable();
            $table->string('app3', 1)->default('0');
            $table->string('app3_jbt', 30)->nullable();
            $table->date('app3_date')->nullable();
            $table->string('app3_empid', 30)->nullable();
            $table->string('catatan_app3', 1000)->nullable();
        });
        Schema::table('sppb_d', function (Blueprint $table) {
            $table->integer('app3_vol')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sppb_h', function (Blueprint $table) {
            $table->dropColumn(['app2_jbt', 'app3', 'app3_jbt', 'app3_date', 'app3_empid', 'catatan_app3']);
        });
        Schema::table('sppb_d', function (Blueprint $table) {
            $table->dropColumn(['app3_vol']);
        });
    }
};
