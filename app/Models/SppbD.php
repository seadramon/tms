<?php

namespace App\Models;

use App\Models\Views\VMasterProduk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppbD extends Model
{
    use HasFactory;

    protected $table = 'SPPB_D';
    protected $primaryKey = 'no_sppb';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;

	public function produk()
	{
		return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
	}

    public function spmd(){
        return $this->belongsTo(SpmD::class, 'kd_produk', 'kd_produk');
    }
    
	public function master_produk(){
        return $this->belongsTo(VMasterProduk::class, 'kd_produk', 'kd_produk');
    }
}
