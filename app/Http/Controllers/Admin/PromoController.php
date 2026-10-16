<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Promo messages sent to customers (stored in the `notifikasis` table).
class PromoController extends Controller
{
    public function index(): View
    {
        return view('admin.promo.index', ['notifikasis' => Notifikasi::latest()->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.promo.create', ['users' => User::orderBy('username')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'id_pesan' => ['required', 'string'],
            'target'   => ['required', 'in:all,user'],
            'id_user'  => ['required_if:target,user', 'nullable', 'exists:users,id_user'],
        ]);

        $recipients = $validated['target'] === 'user'
            ? User::where('id_user', $validated['id_user'])->get()
            : User::where('role', 'customer')->get();

        foreach ($recipients as $user) {
            Notifikasi::create([
                'id_user'   => $user->id_user,
                'id_member' => $user->member?->id_member,
                'id_pesan'  => $validated['id_pesan'],
                'judul'     => $validated['judul'],
            ]);
        }

        return redirect()->route('admin.promo.index')->with('success', 'Pesan promo terkirim ke ' . $recipients->count() . ' pengguna.');
    }

    public function destroy(Notifikasi $promo): RedirectResponse
    {
        $promo->delete();

        return redirect()->route('admin.promo.index')->with('success', 'Pesan dihapus.');
    }
}
