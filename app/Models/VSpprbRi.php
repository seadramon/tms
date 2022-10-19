<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VSpprbRi extends Model
{
    use HasFactory;

    protected $table = 'v_spprb_ri';
    protected $primaryKey = 'pat_to';
	protected $keyType = 'string';
	public $incrementing = false;

	public function produk()
	{
		return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
	}

	public function pat()
	{
		return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
	}
}
