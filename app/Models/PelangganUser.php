<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class PelangganUser extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'tms_pelanggan_users';

    public function pelanggan()
    {
    	return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'pelanggan_id');
    }
}
