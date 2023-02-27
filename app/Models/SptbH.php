<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SptbH extends Model
{
    use HasFactory;

    protected $table = 'SPTB_H';
    protected $primaryKey = 'no_sptb';
	protected $keyType = 'string';
    protected $guarded = [];
	public $incrementing = false;
	public $timestamps = false;
    
    public function sptbd()
    {
    	return $this->hasMany(SptbD::class, 'no_sptb', 'no_sptb');
    }

    public function sptbd2()
    {
    	return $this->hasMany(SptbD2::class, 'no_sptb', 'no_sptb');
    }

    public function spmh()
    {
    	return $this->belongsTo(SpmH::class, 'no_spm', 'no_spm');
    }

    public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }

    public function monOp()
    {
        return $this->belongsTo(MonOp::class, 'no_npp', 'no_npp');
    }

    public function ppb_muat()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }

    public function admProduksi()
    {
        return $this->belongsTo(Personal::class, 'created_by', 'employee_id');
    }

    public function scopeFilterLogin($query, $type, $value)
    {
        if($type == 'vendor'){
            return $query->whereHas('spmh', function($sql) use($value) {
                $sql->whereVendorId($value);
            });
        }
        if($type == 'internal' && $value != '0A'){
            return $query->whereHas('npp', function($sql) use($value) {
                $sql->whereKdPat($value);
            });
        }
        return $query;
    }
}
