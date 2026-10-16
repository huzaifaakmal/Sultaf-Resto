<?php

namespace App\Services;

use App\Models\Member;
use App\Models\TransaksiPenjualan;
use App\Models\User;

class MemberPointService
{
    // Setiap kelipatan Rp 10.000 dari total bayar = 1 poin.
    private const RUPIAH_PER_POIN = 10000;

    /**
     * Tambah poin ke member yang nomor HP-nya cocok.
     * Mengembalikan Member, atau null jika nomor kosong / belum terdaftar sebagai member.
     */
    public function awardFromTransaksi(TransaksiPenjualan $transaksi, ?string $noTelepon): ?Member
    {
        if (blank($noTelepon)) {
            return null;
        }

        $user = User::where('no_hp', $noTelepon)->first();
        $member = $user ? Member::where('id_user', $user->id_user)->first() : null;

        if (! $member) {
            return null;
        }

        $member->increment('total_poin', intdiv((int) $transaksi->total_bayar, self::RUPIAH_PER_POIN));

        return $member;
    }
}
