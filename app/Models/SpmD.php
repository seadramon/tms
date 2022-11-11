<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmD extends Model
{
    use HasFactory;

    protected $table = 'spm_d';
    protected $primaryKey = 'no_spm';
    protected $keyType = 'string';
    public $timestamps = false;
    public $incrementing = false;


    public function produk()
	{
		return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
	}
    public function spmh()
	{
		return $this->belongsTo(SpmH::class, 'no_spm', 'no_spm');
	}
}
