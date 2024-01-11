<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tms_settings';
    protected $guarded = [];
    protected $keyType = 'string';
	public $incrementing = false;

    protected $casts = [
        'data' => 'array',
    ];
}
