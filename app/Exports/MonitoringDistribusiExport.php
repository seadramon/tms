<?php

namespace App\Exports;

use App\Models\SptbH;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MonitoringDistribusiExport implements FromView, WithDrawings
{
    function __construct($minggu1, $minggu2, $kd_pat) {
        $this->minggu1 = $minggu1;
        $this->minggu2 = $minggu2;
        $this->kd_pat = $kd_pat;
    }
 
    public function view(): View
    {
        $query = SptbH::from('sptb_h a')
            ->selectRaw('tgl_sptb, a.no_sptb, a.no_spm, angkutan, a.no_pol, a.no_npp, a.no_spprb, b.nama_pelanggan, b.nama_proyek, c.kd_produk, d.tipe, c.vol')
            ->join('npp b', 'a.no_npp', 'b.no_npp')
            ->join('sptb_d c', 'a.no_sptb', 'c.no_sptb')
            ->join('tb_produk d', 'c.kd_produk', 'd.kd_produk')
            ->join('spm_h e', 'a.no_spm', 'e.no_spm')
            ->whereBetween('tgl_sptb', [DB::raw("to_date('". $this->minggu1 ."','dd/mm/yyyy')"), DB::raw("to_date('". $this->minggu2 ."','dd/mm/yyyy')")])
            ->whereRaw("a.kd_pat = '". $this->kd_pat ."'");
            
        if(Auth::check()){
            $query->whereRaw("e.vendor_id = '". Auth::user()->vendor_id ."'");
        }
        $datas = $query->orderBy('tgl_sptb')->orderBy('no_sptb')->get();

        return view('pages.report.monitoring-distribusi.export-excel', [
            'datas' => $datas, 
            'minggu1' => $this->minggu1,
            'minggu2' => $this->minggu2
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('assets/media/logos/wikabeton.jpg'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}