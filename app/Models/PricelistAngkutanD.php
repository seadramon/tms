<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricelistAngkutanD extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_pricelist_angkutan_d';
    protected $guarded = [];

    public function getUnitMuatAttribute()
    {
        if($this->jenis_muat == 'unit'){
            return $this->pat->ket;
        }elseif($this->jenis_muat == 'vendor'){
            return $this->vendor->nama;
        }else{
            return $this->npp->nama_proyek;
        }
    }

    public function pad2()
    {
    	return $this->hasMany(PricelistAngkutanD2::class, 'pad_id', 'id')->orderBy('id');
    }

    public function angkutan()
    {
    	return $this->belongsTo(TrMaterial::class, 'kd_material', 'kd_material');
    }

    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'kd_muat', 'kd_pat');
    }

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'kd_muat', 'vendor_id');
    }

    public function npp()
    {
    	return $this->belongsTo(Npp::class, 'kd_muat', 'no_npp');
    }
}
