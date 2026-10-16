<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    // Dipanggil saat tombol ID/EN diklik. Menyimpan pilihan bahasa di session
    // lalu kembali ke halaman semula (URL sebelumnya) dengan bahasa baru aktif.
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        $request->session()->put('locale', $locale);
        $request->session()->save();

        return back(fallback: route('home'));
    }
}
