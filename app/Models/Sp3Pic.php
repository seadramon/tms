<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3Pic extends Model
{
    use HasFactory;

    protected $table = 'sp3_pic';
    protected $primaryKey = 'no_sp3';
    protected $keyType = 'string';
    public $timestamps = false;
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(Personal::class, 'employee_id', 'employee_id');
    }
}
