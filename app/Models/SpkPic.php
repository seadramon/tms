<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkPic extends Model
{
    use HasFactory;

    protected $table = 'spk_pic';
    protected $primaryKey = 'no_spk';
    protected $keyType = 'string';
    public $timestamps = false;
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(Personal::class, 'employee_id', 'employee_id');
    }
}
