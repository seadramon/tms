<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sp3 extends Model
{
    use HasFactory;

    protected $table = 'sp3_h';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function vendor()
    {
    	return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}
