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
        Schema::create('tms_armada_rating_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ar_id')->nullable()->constrained('tms_armada_ratings');
            $table->string('criteria', 450);
            $table->string('bobot', 50);
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
        Schema::dropIfExists('tms_armada_rating_details');
    }
};
