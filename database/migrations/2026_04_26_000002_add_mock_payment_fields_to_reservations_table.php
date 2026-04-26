<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_status', 20)
                ->default('unpaid')
                ->after('status')
                ->index();
            $table->string('payment_reference')->nullable()->after('payment_status')->index();
            $table->string('payment_method')->nullable()->after('payment_reference');
            $table->timestamp('payment_paid_at')->nullable()->after('payment_method');
            $table->timestamp('payment_expires_at')->nullable()->after('payment_paid_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_reference',
                'payment_method',
                'payment_paid_at',
                'payment_expires_at',
            ]);
        });
    }
};
