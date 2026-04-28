<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration

{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert Purchase total_amount to cents
        DB::table('purchases')->update([
            'total_amount' => DB::raw('total_amount * 100')
        ]);

        // Convert PurchaseDetails unitcost and total to cents
        DB::table('purchase_details')->update([
            'unitcost' => DB::raw('unitcost * 100'),
            'total' => DB::raw('total * 100')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('purchases')->update([
            'total_amount' => DB::raw('total_amount / 100')
        ]);

        DB::table('purchase_details')->update([
            'unitcost' => DB::raw('unitcost / 100'),
            'total' => DB::raw('total / 100')
        ]);
    }
};
