<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spk extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'spk_h';
    protected $primaryKey = 'no_spk';
    protected $keyType = 'string';
    public $incrementing = false;

    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'last_update_date';

    protected $casts = [
        'data' => 'array',
    ];

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

    public function spk_d()
    {
        return $this->hasMany(SpkD::class, 'no_spk', 'no_spk');
    }

    public function spk_pasal()
    {
        return $this->hasMany(SpkPasal::class, 'no_spk', 'no_spk');
    }

    public function pic()
    {
        return $this->hasMany(SpkPic::class, 'no_spk', 'no_spk');
    }

    public function unitkerja()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }

    public function pihak1_data()
    {
        return $this->belongsTo(Personal::class, 'pihak1', 'employee_id');
    }
}
