<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = TransaksiPenjualan::where('id_user', Auth::user()->id_user)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(TransaksiPenjualan $transaksi): View
    {
        abort_unless($transaksi->id_user === Auth::user()->id_user, 403);

        $transaksi->load('detail_transaksi.menu');

        return view('orders.show', compact('transaksi'));
    }
}
