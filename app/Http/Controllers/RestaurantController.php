<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->get();
        return view('home', compact('menuItems'));
    }
}
