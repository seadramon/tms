<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'tb_produk';
    protected $primaryKey = 'kd_produk';
	protected $keyType = 'string';
	public $incrementing = false;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
}
