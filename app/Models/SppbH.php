<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppbH extends Model
{
    use HasFactory;

    protected $table = 'SPPB_H';
    protected $primaryKey = 'no_sppb';
	protected $keyType = 'string';
	public $incrementing = false;

    public function spprb()
    {
    	return $this->belongsTo(SpprbH::class, 'no_spprb', 'no_spprb');
    }

    public function detail()
    {
    	return $this->hasMany(SppbD::class, 'no_sppb', 'no_sppb');
    }
}
