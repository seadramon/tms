<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sp3;
use App\Models\SppbH;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\Views\VPotensiMuat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $type = Auth::check() ? 'vendor' : 'internal';
        $value = Auth::check() ? Auth::user()->vendor_id : session('TMP_KDWIL');

        $sp3Draft = Sp3::FilterLogin($type, $value)->where('st_wf', 0)->count();
        $sp3BelumVerif = Sp3::FilterLogin($type, $value)->where('st_wf', 1)->where('app1', 0)->count();
        $sp3Aktif = Sp3::FilterLogin($type, $value)->where('st_wf', 1)->where('app1', 1)->count();
        $sp3Selesai = Sp3::FilterLogin($type, $value)->where('jadwal2', '<', $today)->count();

        $spp1 = SppbH::FilterLogin($type, $value)
            ->where(function($sql){
                $sql->whereNull('app')->orWhere('app', 0);
            })
            ->count();
        $sppAktif = SppbH::FilterLogin($type, $value)->where('jadwal2', '>=', $today)->count();
        $sppSelesai = SppbH::FilterLogin($type, $value)->where('jadwal2', '<', $today)->count();

        $spm1 = SpmH::FilterLogin($type, $value)
            ->where(function($sql){
                $sql->whereNull('app2')->orWhere('app2', 0);
            })
            ->count();
        $spmOnProgress = SpmH::FilterLogin($type, $value)->doesntHave('sptbh')->count();
        $sptbOnTerbit = SpmH::FilterLogin($type, $value)->has('sptbh')->count();

        $sptb1 = SptbH::FilterLogin($type, $value)
            ->has('spmh')
            ->where(function($sql){
                $sql->whereNull('app_pelanggan')->orWhere('app_pelanggan', 0);
            })
            ->count();
        $sptb2 = SptbH::FilterLogin($type, $value)->has('spmh')->where('app_pelanggan', 1)->count();

        $potensi1 = VPotensiMuat::FilterLogin($type, $value)->select('no_npp')->where('jenis_armada', 'BELUM DISET')->distinct()->count();
        $potensi2 = VPotensiMuat::FilterLogin($type, $value)->select('no_npp')->where('jenis_armada', '<>', 'BELUM DISET')->doesntHave('sptbh')->distinct()->count();
        
        return view('pages.dashboard.index', compact(
            'sp3Draft',
            'sp3BelumVerif',
            'sp3Aktif',
            'sp3Selesai',
            'sppAktif',
            'sppSelesai',
            'spmOnProgress',
            'sptbOnTerbit',
            'spm1',
            'spp1',
            'sptb1',
            'sptb2',
            'potensi1',
            'potensi2',
        ));
    }
}