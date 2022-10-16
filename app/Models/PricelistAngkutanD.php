<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricelistAngkutanD extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_pricelist_angkutan_d';

    public function pad2()
    {
    	return $this->hasMany(PricelistAngkutanD2::class, 'pad_id', 'id')->orderBy('id');
    }
}
