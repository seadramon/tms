<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class GpsLog extends Model
{
    use HasFactory, Uuid;
    protected $table = 'tms_gps_logs';
    protected $guarded = [];
    protected $keyType = 'string';
	public $incrementing = false;
}
