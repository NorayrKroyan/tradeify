<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trade_posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('type', 10)->index();
            $table->string('status', 20)->default('draft')->index();
            $table->string('title', 180);
            $table->string('product_name');
            $table->text('description');
            $table->string('hs_code', 20)->nullable()->index();
            $table->decimal('quantity', 15, 3)->nullable();
            $table->string('unit', 40)->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('price_terms')->nullable();
            $table->string('incoterm', 20)->nullable();
            $table->char('origin_country_code', 2)->nullable()->index();
            $table->char('destination_country_code', 2)->nullable()->index();
            $table->decimal('minimum_order_quantity', 15, 3)->nullable();
            $table->date('available_from')->nullable();
            $table->date('expires_at')->nullable()->index();
            $table->json('custom_fields')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type', 'category_id']);
            $table->fullText(['title', 'product_name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_posts');
    }
};
