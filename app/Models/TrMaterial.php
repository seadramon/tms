<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrMaterial extends Model
{
    use HasFactory;

    protected $table = 'tr_material';
    protected $primaryKey = 'kd_material';
	protected $keyType = 'string';
	public $incrementing = false;

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $full = collect([!empty($this->uraian)?$this->uraian:"", !empty($this->spesifikasi)?$this->spesifikasi:""])->reject(function ($value, $key) {
            return is_null($value);
        });
        return trim(implode(' ', $full->all()));
    }
}
