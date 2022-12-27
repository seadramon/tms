<?php

namespace App\Models\Views;

use App\Models\Npp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pat;
use App\Models\PotensiH;
use App\Models\SptbH;
use Awobaz\Compoships\Compoships;

class VPotensiMuat extends Model
{
    use HasFactory, Compoships;

    protected $table = 'v_potensi_muat';


    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'ppb_muat', 'kd_pat');
    }
    
    public function ppbmuat()
    {
    	return $this->belongsTo(Pat::class, 'ppb_muat', 'kd_pat');
    }

    public function unitkerja()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }

    public function sptbh()
    {
        return $this->hasMany(SptbH::class, 'no_npp', 'no_npp');
    }

    public function potensiH()
    {
        return $this->belongsTo(PotensiH::class, ['no_npp', 'ppb_muat'], ['no_npp', 'pat_to']);
    }

    public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }

    public function scopeFilterLogin($query, $type, $value)
    {
        // if($type == 'vendor'){
        //     return $query->whereVendorId($value);
        // }
        if($type == 'internal' && $value != '0A'){
            return $query->whereKdPat($value);
        }
        return $query;
    }
}
