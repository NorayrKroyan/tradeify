<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('primary_company_id')->nullable()->after('id')->constrained('companies')->nullOnDelete();
            $table->string('phone', 50)->nullable();
            $table->string('job_title')->nullable();
            $table->string('requested_role', 20)->nullable();
            $table->boolean('is_active')->default(true)->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('primary_company_id');
            $table->dropColumn(['phone', 'job_title', 'requested_role', 'is_active']);
        });
    }
};
