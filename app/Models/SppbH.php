<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppbH extends Model
{
    use HasFactory;

    protected $table = 'SPPB_H';
    protected $primaryKey = 'no_sppb';
	protected $keyType = 'string';
	public $incrementing = false;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function spprb()
    {
    	return $this->belongsTo(SpprbH::class, 'no_spprb', 'no_spprb');
    }

    public function detail()
    {
    	return $this->hasMany(SppbD::class, 'no_sppb', 'no_sppb');
    }

    public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'app_empid', 'employee_id');
    }

    public function personal2()
    {
        return $this->belongsTo(Personal::class, 'app2_empid', 'employee_id');
    }

    public function personal3()
    {
        return $this->belongsTo(Personal::class, 'app3_empid', 'employee_id');
    }

    public function createdby()
    {
        return $this->belongsTo(Personal::class, 'created_by', 'employee_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'app_jbt', 'kd_jbt');
    }

    public function jabatan2()
    {
        return $this->belongsTo(Jabatan::class, 'app2_jbt', 'kd_jbt');
    }

    public function jabatan3()
    {
        return $this->belongsTo(Jabatan::class, 'app3_jbt', 'kd_jbt');
    }

    public function scopeFilterLogin($query, $type, $value)
    {
        if($type == 'internal' && $value != '0A'){
            return $query->whereHas('npp', function($sql) use($value) {
                $sql->whereKdPat($value);
            });
        }
        return $query;
    }
}
