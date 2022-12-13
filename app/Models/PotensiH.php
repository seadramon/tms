<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotensiH extends Model
{
    use HasFactory, Compoships;
    protected $table = 'tms_potensi_h';

    public function potensi_vendors()
    {
        return $this->hasMany(PotensiVendor::class, 'potensi_id');
    }
}
