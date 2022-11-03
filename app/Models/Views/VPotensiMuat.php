<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pat;

class VPotensiMuat extends Model
{
    use HasFactory;

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
}
