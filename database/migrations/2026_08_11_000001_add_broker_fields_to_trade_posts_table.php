<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_posts', function (Blueprint $table): void {
            $table->string('broker_direction', 30)
                ->nullable()
                ->after('type')
                ->index();

            $table->decimal('target_price', 15, 2)
                ->nullable()
                ->after('price')
                ->index();

            $table->string('broker_commission_type', 20)
                ->nullable()
                ->after('target_price');

            $table->decimal('broker_commission_value', 15, 4)
                ->nullable()
                ->after('broker_commission_type');
        });
    }

    public function down(): void
    {
        Schema::table('trade_posts', function (Blueprint $table): void {
            $table->dropColumn([
                'broker_direction',
                'target_price',
                'broker_commission_type',
                'broker_commission_value',
            ]);
        });
    }
};
