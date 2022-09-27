<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpmH extends Model
{
    use HasFactory;

    protected $table = 'spm_h';
    protected $primaryKey = 'no_spm';
    protected $keyType = 'string';
    public $timestamps = false;
    public $incrementing = false;

}
