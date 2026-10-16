<?php

namespace App\Models;

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
}
