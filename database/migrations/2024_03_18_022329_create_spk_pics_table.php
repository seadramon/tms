<?php

use App\Models\Spk;
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
        Schema::create('spk_pic', function (Blueprint $table) {
            $table->string('no_spk', 30);
            $table->string('employee_id', 30);
            $table->foreign('no_spk')->references('no_spk')->on('spk_h');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spk_pic');
    }
};
