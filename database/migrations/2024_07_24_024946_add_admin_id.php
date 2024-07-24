<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('content_configs', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
        });
        Schema::table('telegram_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
        });
        Schema::table('bots', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_configs', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
        Schema::table('telegram_groups', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
    }
};
