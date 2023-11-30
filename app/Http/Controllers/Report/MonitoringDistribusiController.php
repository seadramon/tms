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
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportPdf($minggu1, $minggu2, $kd_pat)
    {
        $query = SptbH::from('sptb_h a')
            ->selectRaw('tgl_sptb, a.no_sptb, a.no_spm, angkutan, a.no_pol, a.no_npp, a.no_spprb, b.nama_pelanggan, b.nama_proyek, c.kd_produk, d.tipe, c.vol')
            ->join('npp b', 'a.no_npp', 'b.no_npp')
            ->join('sptb_d c', 'a.no_sptb', 'c.no_sptb')
            ->join('tb_produk d', 'c.kd_produk', 'd.kd_produk')
            ->join('spm_h e', 'a.no_spm', 'e.no_spm')
            ->whereBetween('tgl_sptb', [DB::raw("to_date('". $minggu1 ."','dd/mm/yyyy')"), DB::raw("to_date('". $minggu2 ."','dd/mm/yyyy')")])
            ->whereRaw("a.kd_pat = '". $kd_pat ."'");

        $lokasi = Pat::find($kd_pat);
            
        if(Auth::check()){
            $query->whereRaw("e.vendor_id = '". Auth::user()->vendor_id ."'");
        }
        $datas = $query->orderBy('tgl_sptb')->orderBy('no_sptb')->get();

        $pdf = Pdf::loadView('pages.report.monitoring-distribusi.export-pdf', [
            'datas' => $datas, 
            'minggu1' => $minggu1,
            'minggu2' => $minggu2,
            'lokasi' => $lokasi
        ]);

        $filename = "Monitoring-Distribusi";

        return $pdf->setPaper('a4', 'landscape')
            ->stream($filename . '.pdf');
    }

}
