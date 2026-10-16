<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu',
        'harga',
        'foto_menu',
        'status_ketersediaan',
        'deskripsi',
        'rating',
        'diet_type',
        'is_halal',
    ];

    protected $casts = [
        'is_halal' => 'boolean',
    ];

    // Aliases so views that still use the older field names keep working.
    // The database columns (see the menus migrations) are the single source of truth.

    protected function id(): Attribute
    {
        return Attribute::get(fn () => $this->getKey());
    }

    protected function namaMakanan(): Attribute
    {
        return Attribute::get(fn () => $this->nama_menu);
    }

    protected function hargaMakanan(): Attribute
    {
        return Attribute::get(fn () => $this->harga);
    }

    protected function fotoMakanan(): Attribute
    {
        return Attribute::get(fn () => $this->foto_menu);
    }

    protected function habis(): Attribute
    {
        return Attribute::get(fn () => $this->status_ketersediaan === 'habis');
    }

    protected function hargaFormat(): Attribute
    {
        return Attribute::get(fn () => 'Rp ' . number_format((float) $this->harga, 0, ',', '.'));
    }
}
