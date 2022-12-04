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
        Schema::table('tms_menus', function (Blueprint $table) {
            $table->text('action')->nullable();
        });
        Schema::table('tms_role_menus', function (Blueprint $table) {
            $table->text('action_menu')->nullable();
        });
        Schema::table('tms_users', function (Blueprint $table) {
            $table->string('vendor_id', 50)->nullable();
        });
        Schema::table('tms_pricelist_angkutan_d', function (Blueprint $table) {
            $table->string('vendors', 400)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tms_menus', function (Blueprint $table) {
            $table->dropColumn(['action']);
        });
        Schema::table('tms_role_menus', function (Blueprint $table) {
            $table->dropColumn(['action_menu']);
        });
        Schema::table('tms_users', function (Blueprint $table) {
            $table->dropColumn(['vendor_id']);
        });
        Schema::table('tms_pricelist_angkutan_d', function (Blueprint $table) {
            $table->dropColumn(['vendors']);
        });
    }
};
