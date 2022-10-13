<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkutanH extends Model
{
    use HasFactory;

    protected $table = 'tms_pricelist_angkutan_h';

    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }
}
