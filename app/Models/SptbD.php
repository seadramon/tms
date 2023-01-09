<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SptbD extends Model
{
    use HasFactory;

    protected $table = 'SPTB_D';
    protected $primaryKey = 'no_sptb';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;

    public function sptbh()
	{
		return $this->belongsTo(SptbH::class, 'no_sptb', 'no_sptb');
	}

	public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }
}
