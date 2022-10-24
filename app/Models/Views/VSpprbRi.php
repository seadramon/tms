<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;

class VSpprbRi extends Model
{
    use HasFactory;

    protected $table = 'v_spprb_ri';

    public function produk(){
		return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
	}
}
