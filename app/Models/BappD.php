<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BappD extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'bapp_d1';
    protected $primaryKey = 'no_bapp';
    protected $keyType = 'string';
    public $incrementing = false;

    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'last_update_date';

    protected $casts = [
        'data' => 'array',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }
    // public function spk_d()
    // {
    //     return $this->hasMany(SpkD::class, 'no_bapp', 'no_bapp');
    // }

    // public function pihak1_data()
    // {
    //     return $this->belongsTo(Personal::class, 'pihak1', 'employee_id');
    // }
}
