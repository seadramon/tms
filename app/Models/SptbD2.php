<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SptbD2 extends Model
{
    use HasFactory;

    protected $table = 'SPTB_D2';
    protected $primaryKey = 'stockid';
	protected $keyType = 'string';
	public $incrementing = false;
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }
}
