<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('legal_name');
            $table->string('trading_name')->nullable();
            $table->string('registration_number', 100);
            $table->string('tax_number', 100);
            $table->string('activity_type', 20);
            $table->char('country_code', 2);
            $table->text('registered_address');
            $table->text('operating_address')->nullable();
            $table->string('website')->nullable();
            $table->string('business_email');
            $table->string('business_phone', 50);
            $table->text('description');
            $table->string('verification_status', 30)->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('verification_expires_at')->nullable()->index();
            $table->text('rejection_reason')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->json('additional_information')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['country_code', 'registration_number']);
            $table->index(['country_code', 'tax_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
