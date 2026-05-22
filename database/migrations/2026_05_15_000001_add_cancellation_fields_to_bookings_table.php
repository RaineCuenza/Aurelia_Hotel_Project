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
        if (! Schema::hasColumn('bookings', 'cancellation_reason')
            || ! Schema::hasColumn('bookings', 'cancelled_by')
            || ! Schema::hasColumn('bookings', 'cancelled_at')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (! Schema::hasColumn('bookings', 'cancellation_reason')) {
                    $table->text('cancellation_reason')->nullable()->after('payment_expires_at');
                }

                if (! Schema::hasColumn('bookings', 'cancelled_by')) {
                    $table->foreignId('cancelled_by')
                        ->nullable()
                        ->after('cancellation_reason')
                        ->constrained('users')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('bookings', 'cancelled_at')) {
                    $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'cancelled_by')) {
                try {
                    $table->dropConstrainedForeignId('cancelled_by');
                } catch (\Throwable $e) {
                    // ignore if constraint already removed
                }
            }

            $cols = [];
            if (Schema::hasColumn('bookings', 'cancellation_reason')) {
                $cols[] = 'cancellation_reason';
            }
            if (Schema::hasColumn('bookings', 'cancelled_at')) {
                $cols[] = 'cancelled_at';
            }

            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};