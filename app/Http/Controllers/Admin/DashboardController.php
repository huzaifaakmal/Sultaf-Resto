<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Safe empty arrays/stats to test if the view loads properly
        $stats = [
            'total_menu' => 0,
            'menu_habis' => 0,
            'total_member' => 0,
            'promosi_aktif' => 0,
        ];

        $menuTerlaris = collect();
        $pesanTerakhir = collect();

        return view('admin.dashboard', compact('user', 'stats', 'menuTerlaris', 'pesanTerakhir'));
    }
}