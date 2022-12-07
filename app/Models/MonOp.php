<?php

namespace App\Models;

use App\Models\Views\VSpprbRi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class MonOp extends Model
{
    use HasFactory, Compoships;

    protected $table = 'mon_op';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk_konfirmasi', 'kd_produk');
    }

    public function sp3D()
    {
        return $this->hasMany(Sp3D::class, 'no_npp', 'no_npp')
            // ->where('kd_produk', $this->kd_konfirmasi_produk)
            ->orderBy('no_sp3', 'desc');
    }

    public function vSpprbRi()
    {
        return $this->belongsTo(VSpprbRi::class, ['no_npp', 'kd_produk_konfirmasi'], ['no_npp', 'kd_produk']);
    }
}
