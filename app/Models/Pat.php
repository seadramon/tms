<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pat extends Model
{
    use HasFactory;

    protected $connection = 'oracle-hrms';
    protected $table = 'tb_pat';
    protected $primaryKey = 'kd_pat';
	protected $keyType = 'string';
	public $incrementing = false;
}
