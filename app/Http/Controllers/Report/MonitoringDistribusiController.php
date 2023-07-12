<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\KalenderMg;
use App\Models\Pat;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\TrMaterial;
use App\Models\Vendor;
use Flasher\Prime\FlasherInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonitoringDistribusiExport;

class MonitoringDistribusiController extends Controller
{
    public function index(){
        $tahun = [];
        for($i=0; $i<5; $i++){
            $year = date('Y', strtotime('-' . $i . ' years'));
            $tahun[$year] = $year;
        }
        $periode_minggu = KalenderMg::whereTh(date('Y'))
			->whereKdPat('1A')
			->get()
			->sortBy(function ($item) {
				return (int) $item->mg;
			})
			->mapWithKeys(function($item){ 
				$awal = date('d-m-Y', strtotime($item->tgl_awal));
				$akhir = date('d-m-Y', strtotime($item->tgl_akhir));
				return [$awal => "Mg " . $item->mg . " ({$awal})"]; 
			})
			->all();

        $kd_pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['2'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        return view('pages.report.monitoring-distribusi.index', compact(
            'kd_pat',
            'periode_minggu',
            'tahun'
        ));
    }

    public function exportExcel($minggu1, $minggu2, $kd_pat){
        return Excel::download(new MonitoringDistribusiExport($minggu1, $minggu2, $kd_pat), 'Monitoring Distribusi.xlsx');
    }

}
