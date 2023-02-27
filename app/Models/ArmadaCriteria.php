<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArmadaCriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_armada_criterias';
    protected $primaryKey = 'id';
}
