<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price');

            $table->string('status')->default('active');

            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
