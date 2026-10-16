<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    // Use Case: Lihat Menu — getAllMenu()
    public function index(Request $request): View
    {
        $categories = collect(); // Empty collection since categories table was removed

        $activeCategory = $request->query('category', null);

        $menus = Menu::when($activeCategory, function ($query) use ($activeCategory) {
                // If a category filter is passed, you can adjust this or leave it open
            })
            ->orderByDesc('rating')
            ->get();

        return view('menu.index', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'menus' => $menus,
        ]);
    }

    // Added to resolve the missing create method error
    public function create(): View
    {
        return view('menu.create');
    }
}