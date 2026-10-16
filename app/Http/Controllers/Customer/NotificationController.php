<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $pesanPromo = Notifikasi::where('id_user', Auth::user()->id_user)->latest()->get();

        return view('notifications.index', compact('pesanPromo'));
    }
}
