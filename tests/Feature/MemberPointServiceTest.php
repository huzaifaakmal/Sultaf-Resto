<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use App\Services\MemberPointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPointServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_awards_points_to_registered_member(): void
    {
        $user = User::create(['username' => 'budi', 'password' => 'x', 'no_hp' => '0811', 'role' => 'customer']);
        $member = Member::create(['id_user' => $user->id_user, 'total_poin' => 0, 'tanggal_daftar' => now()]);
        $trx = TransaksiPenjualan::create([
            'tipe_pesanan' => 'dine_in', 'status_pesanan' => 'pending',
            'metode_pembayaran' => 'cash', 'status_pembayaran' => 'terverifikasi', 'total_bayar' => 55000,
        ]);

        $result = app(MemberPointService::class)->awardFromTransaksi($trx, '0811');

        $this->assertSame($member->id_member, $result->id_member);
        $this->assertSame(5, $member->fresh()->total_poin);
    }

    public function test_returns_null_for_unknown_phone(): void
    {
        $trx = new TransaksiPenjualan(['total_bayar' => 50000]);

        $this->assertNull(app(MemberPointService::class)->awardFromTransaksi($trx, '0000'));
        $this->assertNull(app(MemberPointService::class)->awardFromTransaksi($trx, null));
    }
}
