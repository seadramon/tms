<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppbD extends Model
{
    use HasFactory;

    protected $table = 'SPPB_D';
    protected $primaryKey = 'no_sppb';
	protected $keyType = 'string';
	public $incrementing = false;

}
