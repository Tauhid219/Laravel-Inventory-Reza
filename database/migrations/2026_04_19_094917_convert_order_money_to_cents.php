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
        DB::table('orders')->update([
            'sub_total' => DB::raw('sub_total * 100'),
            'vat'       => DB::raw('vat * 100'),
            'total'     => DB::raw('total * 100'),
            'pay'       => DB::raw('pay * 100'),
            'due'       => DB::raw('due * 100'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('orders')->update([
            'sub_total' => DB::raw('sub_total / 100'),
            'vat'       => DB::raw('vat / 100'),
            'total'     => DB::raw('total / 100'),
            'pay'       => DB::raw('pay / 100'),
            'due'       => DB::raw('due / 100'),
        ]);
    }
};
