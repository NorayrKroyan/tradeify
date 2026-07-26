<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verification_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->string('original_name');
            $table->string('disk', 30)->default('local');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->string('status', 20)->default('pending');
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};
