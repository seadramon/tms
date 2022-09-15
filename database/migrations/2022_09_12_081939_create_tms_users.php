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
        Schema::create('tms_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('user_id', 200)->nullable();
            $table->string('trader_id', 200)->nullable();
            $table->string('username', 200)->nullable();
            $table->string('password', 200);
            $table->string('fullname', 200)->nullable();
            $table->string('phone', 200)->nullable();
            $table->string('position', 200)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('tms_users');
    }
};
