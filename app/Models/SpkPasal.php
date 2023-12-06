<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkPasal extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'spk_pasal';
    protected $primaryKey = 'no_spk';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }

    public function pat()
    {
        return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
    }
}