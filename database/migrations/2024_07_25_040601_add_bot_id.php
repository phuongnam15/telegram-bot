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
        Schema::table('schedule_delete_message', function (Blueprint $table) {
            $table->string('bot_id');
            $table->string('status')->default('off');
            $table->unsignedBigInteger('admin_id');
        });
        Schema::table('schedule_config', function (Blueprint $table) {
            $table->string('bot_id');
            $table->unsignedBigInteger('admin_id');
        });
        Schema::table('telegram_messages', function (Blueprint $table) {
            $table->string('bot_id');
        });
        Schema::table('schedule_group_config', function (Blueprint $table) {
            $table->string('bot_id');
            $table->unsignedBigInteger('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_delete_message', function (Blueprint $table) {
            $table->dropColumn('bot_id');
            $table->dropColumn('status');
        });
        Schema::table('schedule_config', function (Blueprint $table) {
            $table->dropColumn('bot_id');
        });
        Schema::table('telegram_messages', function (Blueprint $table) {
            $table->dropColumn('bot_id');
        });
        Schema::table('schedule_group_config', function (Blueprint $table) {
            $table->dropColumn('bot_id');
        });
    }
};
