<?php

namespace App\Exports;

use App\Models\SptbH;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class MonitoringDistribusiExport implements FromCollection, WithHeadings
{
    function __construct($minggu1, $minggu2, $kd_pat) {
        $this->minggu1 = $minggu1;
        $this->minggu2 = $minggu2;
        $this->kd_pat = $kd_pat;
    }
 
    public function collection()
    {
        return SptbH::from('sptb_h a')
            ->selectRaw('tgl_sptb, a.no_sptb, angkutan, a.no_pol, a.no_npp, a.no_spprb, b.nama_pelanggan, b.nama_proyek, c.kd_produk, d.tipe, c.vol')
            ->join('npp b', 'a.no_npp', 'b.no_npp')
            ->join('sptb_d c', 'a.no_sptb', 'c.no_sptb')
            ->join('tb_produk d', 'c.kd_produk', 'd.kd_produk')
            ->whereBetween('tgl_sptb', [DB::raw("to_date('". $this->minggu1 ."','dd/mm/yyyy')"), DB::raw("to_date('". $this->minggu2 ."','dd/mm/yyyy')")])
            ->whereRaw("a.kd_pat = '". $this->kd_pat ."'")
            ->orderBy('tgl_sptb')
            ->orderBy('no_sptb')
            ->get();
    }

    public function headings(): array
    {
        return [
            'TGL SPTB',
            'NO SPTB',
            'ANGKUTAN',
            'NOPOL',
            'NO NPP',
            'NO SPPRB',
            'NAMA PELANGGAN',
            'NAMA PROYEK',
            'KODE PRODUK',
            'TIPE',
            'VOLUME',
        ];
    }
}