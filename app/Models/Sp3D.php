<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3D extends Model
{
    use HasFactory;

    protected $guarded = ['no_sp3'];
    protected $table = 'sp3_d';
    protected $primaryKey = 'no_sp3';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}