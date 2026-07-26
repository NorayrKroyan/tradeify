<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verification_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('decision', 30);
            $table->text('notes')->nullable();
            $table->json('checks')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_reviews');
    }
};
