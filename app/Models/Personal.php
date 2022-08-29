<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $connection = 'oracle-hrms';
    protected $table = 'personal';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
}
