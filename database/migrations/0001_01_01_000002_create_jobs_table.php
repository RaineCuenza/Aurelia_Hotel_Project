<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Queue tables intentionally omitted for this project.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to keep migrate:refresh rollback history consistent.
    }
};
