<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Menu;
use App\Models\MenuMobile;
use App\Models\RoleMenu;
use App\Models\RoleMenuMobile;
use App\Models\Setting;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class SettingSpkController extends Controller
{
    
    public function index()
    {
        $spk = Setting::whereModul('spk-pasal')->get();
        return view('pages.setting.spk.index');
    }

    
}