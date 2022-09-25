<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Npp extends Model
{
    use HasFactory;

    protected $table = 'npp';
    protected $primaryKey = 'no_npp';
    protected $keyType = 'string';
    public $timestamps = false;

    public function infoPasar()
    {
        return $this->belongsTo(InfoPasar::class, 'no_info', 'no_info');
    }

    public function pat()
    {
    	return $this->belongsTo(Pat::class, 'kd_pat', 'kd_pat');
    }
}
