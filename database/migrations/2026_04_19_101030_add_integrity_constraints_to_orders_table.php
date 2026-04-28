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
        Schema::table('orders', function (Blueprint $table) {
            // Add indexes for common query patterns
            $table->index('customer_id');
            $table->index('order_status');
            $table->index('payment_type');
            $table->index('order_date');
            
            // Note: SQLite does not support CHECK constraints via Schema builder easily.
            // For MySQL 8.0.16+, we could add:
            // $table->check('pay + due = total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['order_status']);
            $table->dropIndex(['payment_type']);
            $table->dropIndex(['order_date']);
        });
    }
};
