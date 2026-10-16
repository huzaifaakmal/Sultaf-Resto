<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama_menu');
            $table->decimal('rating', 2, 1)->default(0)->after('foto_menu');
            $table->string('diet_type')->nullable()->after('rating');
            $table->boolean('is_halal')->default(true)->after('diet_type');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'rating', 'diet_type', 'is_halal']);
        });
    }
};
