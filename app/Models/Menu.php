<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus'; // Ensures it maps to your 'menus' table

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'rating',
        'habis',
        'image',
    ];
}