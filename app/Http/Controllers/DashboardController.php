<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sp3;
use App\Models\SppbH;
use App\Models\SpmH;

class DashboardController extends Controller
{
    public function index()
    {
        $sp3Draft = Sp3::where('st_wf', 0)->count();
        $sp3BelumVerif = Sp3::where('st_wf', 1)->where('app1', 0)->count();
        $sp3Aktif = Sp3::where('st_wf', 1)->where('app1', 1)->count();

        $today = date('Y-m-d');
        $sppAktif = SppbH::where('jadwal2', '<=', $today)->count();
        $sppSelesai = SppbH::where('jadwal2', '>', $today)->count();

        $spmOnProgress = SpmH::doesntHave('sptbh')->count();
        $sptbOnTerbit = SpmH::whereHas('sptbh')->count();
        
        return view('pages.dashboard.index', compact(
            'sp3Draft',
            'sp3BelumVerif',
            'sp3Aktif',
            'sppAktif',
            'sppSelesai',
            'spmOnProgress',
            'sptbOnTerbit',
        ));
    }
}