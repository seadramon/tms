<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3D2 extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'sp3_d2';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
}