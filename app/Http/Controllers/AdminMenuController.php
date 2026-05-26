<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->get();
        $categories = \App\Models\Category::all();
        return view('admin.dashboard', compact('menuItems', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        MenuItem::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => 'images/' . $imageName,
        ]);

        return back()->with('success', 'Menu item added successfully.');
    }

    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $request->validate(['price' => 'required|numeric']);
        $item->update(['price' => $request->price]);

        return back()->with('success', 'Price updated successfully.');
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Menu item removed successfully.');
    }
}
