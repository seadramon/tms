<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $groups = Group::all();
        foreach ($groups as $group) {
            $role = Role::firstOrNew([
                'grpid' => $group->roleid
            ]);
            $role->name = $group->ket;
            $role->save();
        }
    }
}
