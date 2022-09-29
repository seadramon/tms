<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sbu extends Model
{
    use HasFactory;

    protected $guarded = ['singkatan'];
    protected $table = 'tb_sbu';
    protected $primaryKey = 'kd_sbu';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}