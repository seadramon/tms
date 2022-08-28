<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3 extends Model
{
    use HasFactory;

    protected $guarded = ['no_sp3'];
    protected $table = 'sp3_h';
    protected $primaryKey = 'no_sp3';
    protected $keyType = 'string';
    public $incrementing = false;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}
