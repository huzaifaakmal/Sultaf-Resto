<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Notifikasi;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $stats = [
            'total_menu' => Menu::count(),
            'menu_habis' => Menu::where('status_ketersediaan', 'habis')->count(),
            'total_member' => Member::count(),
            'promosi_aktif' => Promo::count(),
        ];

        $menuTerlaris = DetailTransaksi::selectRaw('id_menu, SUM(jumlah) as total_terjual')
            ->groupBy('id_menu')
            ->orderByDesc('total_terjual')
            ->with('menu')
            ->limit(5)
            ->get()
            ->map(fn ($d) => ['menu' => $d->menu, 'qty' => (int) $d->total_terjual]);

        $pesanTerakhir = Notifikasi::latest()->limit(5)->get();

        return view('admin.dashboard', compact('user', 'stats', 'menuTerlaris', 'pesanTerakhir'));
    }
}
