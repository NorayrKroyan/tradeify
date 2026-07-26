<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trade_post_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('trade_post_id')->constrained()->cascadeOnDelete();
            $table->string('disk', 30)->default('public');
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_post_images');
    }
};
