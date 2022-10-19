<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3 extends Model
{
    use HasFactory;

    protected $guarded = ['no_sp3'];
    protected $table = 'sp3_h';
    protected $primaryKey = 'no_sp3';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }

    public function jenisPekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class, 'kd_jpekerjaan', 'kd_jpekerjaan');
    }

    public function detailPesanan()
    {
        return $this->hasMany(MonOp::class, 'no_npp', 'no_npp');
    }
    
    public function sp3D()
    {
        return $this->hasMany(Sp3D::class, 'no_sp3', 'no_sp3');
    }

    public function sp3D2()
    {
        return $this->hasMany(Sp3D2::class, 'no_sp3', 'no_sp3');
    }
    
    public function ban()
    {
        return $this->belongsTo(Ban::class, 'no_ban', 'no_ban');
    }

    public function pic()
    {
        return $this->hasMany(Sp3Pic::class, 'no_sp3', 'no_sp3');
    }

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class, 'no_kontrak_induk', 'no_kontrak');
    }

    public function dokumen()
    {
        return $this->hasMany(Sp3Dokumen::class, 'no_sp3', 'no_sp3');
    }

    public function vSpprbRi()
    {
        return $this->belongsTo('App\Models\View\VSpprbRi', 'no_npp', 'no_npp');
    }
}
