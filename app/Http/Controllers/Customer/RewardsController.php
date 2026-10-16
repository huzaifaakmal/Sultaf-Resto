<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RewardsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Empty collection for benefits since the benefits table was removed
        $benefits = collect();

        $pointsHistory = TransaksiPenjualan::where('id_user', $user->id_user)
            ->where('status_pembayaran', 'lunas')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($t) => [
                'transaksi' => $t,
                'poin' => 0, // Fallback points calculation
            ]);

        $activeDiscountBenefit = null;
        $nextBenefit = null;

        return view('rewards.index', compact(
            'user', 'benefits', 'pointsHistory', 'activeDiscountBenefit', 'nextBenefit'
        ));
    }
}