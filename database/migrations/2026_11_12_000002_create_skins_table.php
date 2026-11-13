<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('model', 8)->default('classic'); // classic | slim
            $table->string('path');                          // storage/app/skins/<hash>.png
            $table->string('hash', 40);                      // sha1 PNG — щоб не підписувати однаковий файл двічі
            $table->string('status', 16)->default('pending'); // pending | approved | rejected
            $table->text('texture_value')->nullable();       // підпис Mojang через MineSkin: бачать усі клієнти
            $table->text('texture_signature')->nullable();
            $table->string('sign_error', 255)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skins');
    }
};
