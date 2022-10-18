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

    public function sptbd()
    {
    	return $this->hasMany(SptbD::class, 'no_sptb', 'no_sptb');
    }

    public function sptbd2()
    {
    	return $this->hasMany(SptbD2::class, 'no_sptb', 'no_sptb');
    }

    public function spmh()
    {
    	return $this->belongsTo(SpmH::class, 'no_spm', 'no_spm');
    }

    public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }
}
