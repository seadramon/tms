<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
   
    protected $connection = 'oracle-all';
    protected $table = 'usradm.tb_role';
    protected $primaryKey = 'roleid';
	protected $keyType = 'string';
    public $incrementing = false;

}
