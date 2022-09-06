<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PphH extends Model
{
    use HasFactory;

    protected $table = 'tb_pph_h';
    protected $primaryKey = 'pph_id';
	// protected $keyType = 'string';
	// public $incrementing = false;

    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'last_update_date';

}
