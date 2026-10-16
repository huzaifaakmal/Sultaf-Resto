<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualans';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'kode_transaksi', 'id_user', 'nama_pelanggan', 'no_telepon', 'id_promo',
        'tipe_pesanan', 'status_pesanan', 'metode_pembayaran', 'status_pembayaran',
        'bukti_pembayaran', 'total_bayar', 'uang_bayar', 'no_meja', 'diskon', 'catatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }

    public function detail_transaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Aliases for the field names some views still use. The table columns are the source of truth.

    public function items(): HasMany
    {
        return $this->detail_transaksi();
    }

    protected function totalHarga(): Attribute
    {
        return Attribute::get(fn () => $this->total_bayar);
    }

    protected function nomorMeja(): Attribute
    {
        return Attribute::get(fn () => $this->no_meja);
    }

    protected function tglTransaksi(): Attribute
    {
        return Attribute::get(fn () => $this->created_at);
    }

    protected function diskonMember(): Attribute
    {
        return Attribute::get(fn () => $this->diskon);
    }

    protected function subtotal(): Attribute
    {
        return Attribute::get(fn () => (int) $this->detail_transaksi->sum('subtotal'));
    }

    protected function pajak(): Attribute
    {
        return Attribute::get(fn () => max(0, (int) $this->total_bayar + (int) $this->diskon - $this->subtotal));
    }
}
