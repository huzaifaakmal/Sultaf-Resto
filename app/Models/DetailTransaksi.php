<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksis';

    protected $fillable = ['id_transaksi', 'id_menu', 'jumlah', 'subtotal', 'harga_satuan'];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi', 'id_transaksi');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    // Alias for views that read `qty`.
    protected function qty(): Attribute
    {
        return Attribute::get(fn () => $this->jumlah);
    }
}
