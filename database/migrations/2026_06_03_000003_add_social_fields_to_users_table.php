<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('discord_username')->nullable()->after('balance');
            $table->timestamp('discord_linked_at')->nullable()->after('discord_username');

            $table->string('telegram_username')->nullable()->after('discord_linked_at');
            $table->timestamp('telegram_linked_at')->nullable()->after('telegram_username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'discord_username',
                'discord_linked_at',
                'telegram_username',
                'telegram_linked_at',
            ]);
        });
    }
};
