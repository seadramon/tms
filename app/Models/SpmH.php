<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmH extends Model
{
    use HasFactory;

    protected $table = 'spm_h';
    protected $primaryKey = 'no_spm';
    protected $keyType = 'string';
    public $timestamps = false;
    public $incrementing = false;

    public function spmd()
    {
    	return $this->hasMany(SpmD::class, 'no_spm', 'no_spm');
    }

    public function sppb()
    {
        return $this->belongsTo(SppbH::class, 'no_sppb', 'no_sppb');
    }

    public function vendornya()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
    
    public function sppbd()
    {
    	return $this->hasMany(SppbD::class, 'no_sppb', 'no_sppb');
    }

    public function sppbh()
    {
        return $this->belongsTo(SppbH::class, 'no_sppb', 'no_sppb');
    }

    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
    }

    public function sptbh()
    {
        return $this->belongsTo(SptbH::class, 'no_spm', 'no_spm');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'no_pol', 'nopol');
    }

    public function approval()
    {
        return $this->belongsTo(Personal::class, 'app1_empid', 'employee_id');
    }

    public function admDistribusi()
    {
        return $this->belongsTo(Personal::class, 'created_by', 'employee_id');
    }

    public function scopeFilterLogin($query, $type, $value)
    {
        if($type == 'vendor'){
            return $query->whereVendorId($value);
        }
        if($type == 'internal' && $value != '0A'){
            return $query->whereHas('sppbh.npp', function($sql) use($value) {
                $sql->whereKdPat($value);
            });
        }
        return $query;
    }
}
