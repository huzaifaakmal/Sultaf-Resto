<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->unique()->after('name');
            }
        });

        // Populate existing users with email and name
        $defaults = [
            'admin'    => ['name' => 'Administrator Sultaf', 'email' => 'admin@sultaf.com'],
            'owner'    => ['name' => 'Owner Sultaf',         'email' => 'owner@sultaf.com'],
            'kasir'    => ['name' => 'Kasir Sultaf',         'email' => 'kasir@sultaf.com'],
            'dapur'    => ['name' => 'Dapur Sultaf',         'email' => 'dapur@sultaf.com'],
            'customer' => ['name' => 'Pelanggan Setia',      'email' => 'customer@sultaf.com'],
        ];

        foreach ($defaults as $username => $data) {
            DB::table('users')->where('username', $username)->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'email']);
        });
    }
};
