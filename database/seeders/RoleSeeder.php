<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin Role
        if (!Role::where('name', 'super-admin')->exists()) {
            Role::create(['name' => 'super-admin']);
        }

        // Admin Role
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Passive Admin Role
        if (!Role::where('name', 'passive-admin')->exists()) {
            Role::create(['name' => 'passive-admin']);
        }

        // Printer Admin Role
        if (!Role::where('name', 'printer-admin')->exists()) {
            Role::create(['name' => 'printer-admin']);
        }

        // Scanner Admin Role
        if (!Role::where('name', 'scanner-admin')->exists()) {
            Role::create(['name' => 'scanner-admin']);
        }

        // Server Admin Role
        if (!Role::where('name', 'server-admin')->exists()) {
            Role::create(['name' => 'server-admin']);
        }

        // Storage Admin Role
        if (!Role::where('name', 'storage-admin')->exists()) {
            Role::create(['name' => 'storage-admin']);
        }

        // Server Accessories Admin Role
        if (!Role::where('name', 'server-accessories-admin')->exists()) {
            Role::create(['name' => 'server-accessories-admin']);
        }

        // Network Wired Admin Role
        if (!Role::where('name', 'network-wired-admin')->exists()) {
            Role::create(['name' => 'network-wired-admin']);
        }

        // Network Tools Admin Role
        if (!Role::where('name', 'network-tools-admin')->exists()) {
            Role::create(['name' => 'network-tools-admin']);
        }

        // Network Wireless Admin Role
        if (!Role::where('name', 'network-wireless-admin')->exists()) {
            Role::create(['name' => 'network-wireless-admin']);
        }

        // Network Security Admin Role
        if (!Role::where('name', 'network-security-admin')->exists()) {
            Role::create(['name' => 'network-security-admin']);
        }

        // General Others Admin Role
        if (!Role::where('name', 'general-others-admin')->exists()) {
            Role::create(['name' => 'general-others-admin']);
        }

        // Office Accessories Admin Role
        if (!Role::where('name', 'office-accessories-admin')->exists()) {
            Role::create(['name' => 'office-accessories-admin']);
        }

        // Office Statioeries Admin Role
        if (!Role::where('name', 'office-stationeries-admin')->exists()) {
            Role::create(['name' => 'office-stationeries-admin']);
        }
    }
}
