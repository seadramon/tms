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
        // $spk = Setting::whereModul('spk-pasal')->get();
        return view('pages.setting.spk.index', [
            'spk' => null
        ]);
    }

    public function store(Request $request){
        // return response()->json($request->all());
        Setting::whereModul('spk-pasal')->delete();
        foreach ($request->pasal as $index => $pasal) {
            $setting = new Setting;
            $setting->modul = 'spk-pasal';
            $setting->kode = 'pasal-' . sprintf('%02s', $index + 1);
            $setting->data = [
                'judul' => $pasal->pasal_judul,
                'isi' => $pasal->pasal_isi
            ];
            $setting->save();
        }

        return redirect()->route('setting-spk.index');
    }
}