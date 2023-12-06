<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkD extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'spk_d';
    protected $primaryKey = 'no_spk';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }
    public function spk_h()
    {
        return $this->belongsTo(Spk::class, 'no_spk', 'no_spk');
    }

    public function pat()
    {
        return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
    }
}