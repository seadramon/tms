<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SptbH extends Model
{
    use HasFactory;

    protected $table = 'SPTB_H';
    protected $primaryKey = 'no_sptb';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = false;
}
