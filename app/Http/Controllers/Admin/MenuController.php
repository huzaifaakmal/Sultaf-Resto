<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $menus = Menu::when($request->query('q'), fn ($q, $search) => $q->where('nama_menu', 'like', "%{$search}%"))
            ->orderBy('nama_menu')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menu.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menu.create', ['categories' => Category::all()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Menu::create($this->validated($request));

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $menu->update($this->validated($request, $menu));

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * The create form and the edit form post slightly different field names
     * (nama_makanan/harga_makanan vs nama_menu/harga); accept both and map to the table columns.
     */
    private function validated(Request $request, ?Menu $menu = null): array
    {
        $request->validate([
            'nama_menu'           => ['required_without:nama_makanan', 'nullable', 'string', 'max:255'],
            'nama_makanan'        => ['required_without:nama_menu', 'nullable', 'string', 'max:255'],
            'harga'               => ['required_without:harga_makanan', 'nullable', 'numeric', 'min:0'],
            'harga_makanan'       => ['required_without:harga', 'nullable', 'numeric', 'min:0'],
            'deskripsi'           => ['nullable', 'string'],
            'rating'              => ['nullable', 'numeric', 'between:0,5'],
            'diet_type'           => ['nullable', 'string', 'max:50'],
            'status_ketersediaan' => ['nullable', 'in:tersedia,habis'],
            'foto'                => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'nama_menu' => $request->input('nama_menu', $request->input('nama_makanan')),
            'harga'     => $request->input('harga', $request->input('harga_makanan')),
            'status_ketersediaan' => $request->has('status_ketersediaan')
                ? $request->input('status_ketersediaan')
                : ($request->boolean('habis') ? 'habis' : 'tersedia'),
            'is_halal'  => $request->boolean('is_halal', $menu?->is_halal ?? true),
        ];

        foreach (['deskripsi', 'rating', 'diet_type'] as $field) {
            if ($request->filled($field)) {
                $data[$field] = $request->input($field);
            }
        }

        if ($request->hasFile('foto')) {
            $data['foto_menu'] = $request->file('foto')->store('menu', 'public');
        }

        return $data;
    }
}
