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
        Schema::table('sp3_h', function (Blueprint $table) {
            $table->string('app2', 1)->default('0');
            $table->date('app2_date')->nullable();
            $table->string('app2_empid', 30)->nullable();
            $table->string('satuan_harsat', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sp3_h', function (Blueprint $table) {
            $table->dropColumn(['app2', 'app2_date', 'app2_empid', 'satuan_harsat']);
        });
    }
};
