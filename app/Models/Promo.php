<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';
    protected $primaryKey = 'id_promo';

    protected $fillable = ['nama_promo', 'potongan_harga', 'minimal_poin'];
}
