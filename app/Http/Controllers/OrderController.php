<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Services\RmsIntegrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = TransaksiPenjualan::where('user_id', Auth::id())
            ->latest('tgl_transaksi')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(TransaksiPenjualan $transaksi, RmsIntegrationService $rms): View
    {
        $transaksi->load('items.menu');

        // Real-time synchronization of order status with RMS
        if ($rmsData = $rms->fetchOrderStatusFromRms($transaksi->kode_transaksi)) {
            $updated = false;
            if (!empty($rmsData['status_pesanan']) && $transaksi->status_pesanan !== $rmsData['status_pesanan']) {
                $transaksi->status_pesanan = $rmsData['status_pesanan'];
                $updated = true;
            }
            if (!empty($rmsData['status_pembayaran']) && $transaksi->status_pembayaran !== $rmsData['status_pembayaran']) {
                $transaksi->status_pembayaran = $rmsData['status_pembayaran'];
                $updated = true;
            }
            if ($updated) {
                $transaksi->save();
            }
        }

        return view('orders.show', compact('transaksi'));
    }
}
