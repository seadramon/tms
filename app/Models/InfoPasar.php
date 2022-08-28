<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPasar extends Model
{
    use HasFactory;

    protected $table = 'info_pasar_h';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function region()
    {
        return $this->belongsTo(Region::class, 'kd_region', 'kd_region');
    }
}
