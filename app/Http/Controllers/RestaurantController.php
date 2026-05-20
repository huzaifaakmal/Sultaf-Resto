<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('home', compact('menuItems'));
    }
}
