<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbSurat extends Model
{
    use HasFactory;

    protected $table = 'tb_surat';
    protected $primaryKey = 'no_surat';
	protected $keyType = 'string';
	public $incrementing = false;

    

    // public function scopeAngkutan($query)
    // {
    //     return $query->where('kd_jmaterial', 'T')->where('kd_material', 'NOT LIKE', 'TC%');
    // }
}
