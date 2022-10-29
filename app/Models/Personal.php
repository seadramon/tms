<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $connection = 'oracle-hrms';
    protected $table = 'personal';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    protected $appends = ["full_name"];

    public function getFullNameAttribute()
    {
        return $this->first_title .' '. $this->first_name . ' ' . $this->last_name . ' ' . $this->last_title;
    }

    public function jabatan()
    {
    	return $this->belongsTo(Jabatan::class, 'kd_jbt', 'kd_jbt');
    }
}
