<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

use App\Models\Produk;
use App\Models\Pat;
use App\Models\SptbH;

class VMasterProduk extends Model
{
    use HasFactory, Compoships;

    protected $table = 'view_master_produk';

    // public function produk(){
	// 	return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
	// }

    // public function ppb_muat()
    // {
    // 	return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
    // }
}
