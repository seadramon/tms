<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricelistAngkutanH extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_pricelist_angkutan_h';
    protected $guarded = [];

    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }

    public function pad()
    {
    	return $this->hasMany(PricelistAngkutanD::class, 'pah_id', 'id')->orderBy('id');
    }
}
