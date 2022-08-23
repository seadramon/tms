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
}
