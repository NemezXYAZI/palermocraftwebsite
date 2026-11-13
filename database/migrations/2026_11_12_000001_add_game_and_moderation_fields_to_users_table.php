<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 16)->default('player')->after('balance');

            // Прив'язка до ігрового акаунта (підтверджується кодом із гри)
            $table->string('minecraft_name', 16)->nullable()->unique()->after('role');
            $table->uuid('minecraft_uuid')->nullable()->after('minecraft_name');
            $table->timestamp('minecraft_linked_at')->nullable()->after('minecraft_uuid');

            // Справжні ідентифікатори соцмереж (раніше були лише «заглушки» з кодом)
            $table->string('discord_id', 32)->nullable()->unique()->after('discord_username');
            $table->unsignedBigInteger('telegram_id')->nullable()->unique()->after('telegram_username');

            // Бан діє і на сайті, і в грі (плагін перевіряє при вході)
            $table->timestamp('banned_at')->nullable();
            $table->string('ban_reason', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['minecraft_name']);
            $table->dropUnique(['discord_id']);
            $table->dropUnique(['telegram_id']);
            $table->dropColumn([
                'role', 'minecraft_name', 'minecraft_uuid', 'minecraft_linked_at',
                'discord_id', 'telegram_id', 'banned_at', 'ban_reason',
            ]);
        });
    }
};
