<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sp3;
use App\Models\SppbH;
use App\Models\SpmH;
use App\Models\SptbH;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');

        $sp3Draft = Sp3::where('st_wf', 0)->count();
        $sp3BelumVerif = Sp3::where('st_wf', 1)->where('app1', 0)->count();
        $sp3Aktif = Sp3::where('st_wf', 1)->where('app1', 1)->count();
        $sp3Selesai = Sp3::where('jadwal2', '<', $today)->count();

        $spp1 = SppbH::whereNull('app')->orWhere('app', 0)->count();
        $sppAktif = SppbH::where('jadwal2', '>=', $today)->count();
        $sppSelesai = SppbH::where('jadwal2', '<', $today)->count();

        $spm1 = SpmH::whereNull('app2')->orWhere('app2', 0)->count();
        $spmOnProgress = SpmH::doesntHave('sptbh')->count();
        $sptbOnTerbit = SpmH::whereHas('sptbh')->count();

        $sptb1 = SptbH::whereHas('spmh')->whereNull('app_pelanggan')->orWhere('app_pelanggan', 0)->count();
        $sptb2 = SptbH::whereHas('spmh')->where('app_pelanggan', 1)->count();
        
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
        ));
    }
}