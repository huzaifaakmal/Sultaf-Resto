<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\PromoMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $pesanPromo = PromoMessage::untukUser(Auth::user());

        return view('notifications.index', compact('pesanPromo'));
    }
}
