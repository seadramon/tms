<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spnpp extends Model
{
    use HasFactory;

    protected $table = 'spnpp';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
}
