<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tms_roles";


    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'tms_role_menus');
    }
}
