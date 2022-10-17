<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricelistAngkutanD2 extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_pricelist_angkutan_d2';
    protected $guarded = [];
}
