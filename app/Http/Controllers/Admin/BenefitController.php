<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Benefits are stored in the `promos` table (nama_promo, potongan_harga, minimal_poin).
class BenefitController extends Controller
{
    public function index(): View
    {
        return view('admin.benefit.index', ['benefits' => Promo::latest()->get()]);
    }

    public function create(): View
    {
        return view('admin.benefit.create', ['menus' => Menu::orderBy('nama_menu')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Promo::create($this->validated($request));

        return redirect()->route('admin.benefit.index')->with('success', 'Promosi berhasil ditambahkan.');
    }

    public function edit(Promo $benefit): View
    {
        return view('admin.benefit.edit', ['benefit' => $benefit, 'menus' => Menu::orderBy('nama_menu')->get()]);
    }

    public function update(Request $request, Promo $benefit): RedirectResponse
    {
        $benefit->update($this->validated($request));

        return redirect()->route('admin.benefit.index')->with('success', 'Promosi berhasil diperbarui.');
    }

    public function destroy(Promo $benefit): RedirectResponse
    {
        $benefit->delete();

        return redirect()->route('admin.benefit.index')->with('success', 'Promosi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'nama_benefit'    => ['required', 'string', 'max:255'],
            'poin_dibutuhkan' => ['required', 'integer', 'min:0'],
            'nilai_diskon'    => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            'nama_promo'     => $validated['nama_benefit'],
            'minimal_poin'   => $validated['poin_dibutuhkan'],
            'potongan_harga' => $validated['nilai_diskon'] ?? 0,
        ];
    }
}
