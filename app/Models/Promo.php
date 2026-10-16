<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promos';
    protected $primaryKey = 'id_promo';

    protected $fillable = ['nama_promo', 'potongan_harga', 'minimal_poin'];

    // Aliases for the field names used by the admin benefit/rewards views.
    protected function namaBenefit(): Attribute
    {
        return Attribute::get(fn () => $this->nama_promo);
    }

    protected function poinDibutuhkan(): Attribute
    {
        return Attribute::get(fn () => $this->minimal_poin);
    }

    protected function nilaiDiskon(): Attribute
    {
        return Attribute::get(fn () => $this->potongan_harga);
    }

    protected function tipe(): Attribute
    {
        return Attribute::get(fn () => 'diskon');
    }
}
