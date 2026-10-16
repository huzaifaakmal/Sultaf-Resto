<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_penjualans', 'kode_transaksi')) {
                $table->string('kode_transaksi')->nullable()->after('id_transaksi');
            }
            if (!Schema::hasColumn('transaksi_penjualans', 'nama_pelanggan')) {
                $table->string('nama_pelanggan')->nullable()->after('id_user');
            }
            if (!Schema::hasColumn('transaksi_penjualans', 'no_telepon')) {
                $table->string('no_telepon')->nullable()->after('nama_pelanggan');
            }
            if (!Schema::hasColumn('transaksi_penjualans', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('transaksi_penjualans', 'catatan')) {
                $table->text('catatan')->nullable()->after('diskon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->dropColumn(['kode_transaksi', 'nama_pelanggan', 'no_telepon', 'bukti_pembayaran', 'catatan']);
        });
    }
};
