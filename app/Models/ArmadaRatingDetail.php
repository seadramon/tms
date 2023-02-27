<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArmadaRatingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_armada_rating_details';
    protected $primaryKey = 'id';
}
