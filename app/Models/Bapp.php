<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bapp extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'bapp_h1';
    protected $primaryKey = 'no_bapp';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    protected $casts = [
        'data' => 'array',
    ];

    public function bapp_d()
    {
        return $this->hasMany(BappD::class, 'no_bapp', 'no_bapp');
    }

    public function pihak1_data()
    {
        return $this->belongsTo(Personal::class, 'pihak1', 'employee_id');
    }

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}
