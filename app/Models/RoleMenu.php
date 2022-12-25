<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;

    protected $table = "tms_role_menus";
    protected $guarded = [];


    protected $casts = [
        'action_menu' => 'array',
    ];

    public function role()
	{
		return $this->belongsTo(Role::class, 'role_id');
	}

    public function menu()
	{
		return $this->belongsTo(Menu::class, 'menu_id');
	}

}
