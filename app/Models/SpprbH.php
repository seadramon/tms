<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpprbH extends Model
{
    use HasFactory;

    protected $table = 'SPPRB_H';
    protected $primaryKey = 'no_spprb';
	protected $keyType = 'string';
	public $incrementing = false;

	public function sp3()
	{
		// return $this->
	}

	public function pat()
    {
        return $this->belongsTo(Pat::class, 'pat_to', 'kd_pat');
    }

	public function npp()
    {
        return $this->belongsTo(Npp::class, 'no_npp', 'no_npp');
    }
}
