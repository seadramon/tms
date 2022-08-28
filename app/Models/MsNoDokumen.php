<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsNoDokumen extends Model
{
    use HasFactory;

    protected $guarded = ['no_dokumen'];
    protected $table = 'ms_no_dokumen';
    protected $primaryKey = 'no_dokumen';
    protected $keyType = 'string';
    public $incrementing = false;
    
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
}
