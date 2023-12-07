<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\KalenderMg;
use App\Models\MonOp;
use App\Models\Pat;
use App\Models\Sp3;
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

class ProyekBerjalanController extends Controller
{
    public function index(){
        $labelSemua = ["" => "Semua"];

        $kd_pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $kd_pat = $labelSemua + $kd_pat;

        $ppb_muat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['2', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $ppb_muat = $labelSemua + $ppb_muat;
        
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

        return view('pages.report.proyek-berjalan.index', compact(
            'kd_pat',
            'ppb_muat',
            'periode_minggu',
            'tahun',
        ));
    }

    public function data(Request $request)
    {
        $filter_kdpat = "";
        $filter_ppbmuat = "";
        $filter_sptb = "";
        if($request->kd_pat){
            $filter_kdpat = "and a.kd_pat='" . $request->kd_pat . "'";
        }
        if($request->ppb_muat){
            $filter_ppbmuat .= "and d.pat_to='" . $request->ppb_muat . "'";
        }
        if($request->minggu1){
            $m1 = explode(';', $request->minggu1);
            $m2 = explode(';', $request->minggu2);
            $filter_sptb = "and tgl_sptb between '" . date('Y-m-d', strtotime($m1[0])) . "' and '" . date('Y-m-d', strtotime($m2[1])) . "'";
        }
        $raw_query = "SELECT
                a.no_npp,
                a.nama_pelanggan,
                a.nama_proyek,
                c.tipe,
                c.panjang,
                b.vol_konfirmasi AS vol_kontrak,
                d.vol_spprb,
                d.vol_baik AS vol_produksi,
                sum(f.vol) vol_distribusi,
                g.ket AS pabrik
            FROM
                npp a
            INNER JOIN mon_op b ON
                a.no_npp = b.no_npp
            INNER JOIN tb_produk c ON
                b.kd_produk_konfirmasi = c.kd_produk
            INNER JOIN v_spprb_ri d ON
                a.no_npp = d.no_npp
                AND b.kd_produk_konfirmasi = d.kd_produk
            INNER JOIN (
                SELECT
                    no_sptb,
                    no_npp,
                    kd_pat
                FROM
                    sptb_h
                WHERE
                    jns_sptb = 0 " . $filter_sptb . ")e ON
                a.no_npp = e.no_npp
                AND e.kd_pat = d.pat_to
            INNER JOIN sptb_d f ON
                e.no_sptb = f.no_sptb
                AND b.kd_produk_konfirmasi = f.kd_produk
            INNER JOIN hrms.tb_pat g ON
                d.pat_to = g.kd_pat
            INNER JOIN mon_op_d h ON
                a.no_npp = h.no_npp
                AND b.kd_produk_konfirmasi = h.kd_produk
            WHERE
                nilai_kontrak > jml_pengajuan " . $filter_kdpat . " " . $filter_ppbmuat ."
            GROUP BY
                a.no_npp,
                a.nama_pelanggan,
                a.nama_proyek,
                c.tipe,
                c.panjang,
                b.vol_konfirmasi,
                d.vol_baik,
                g.ket,
                d.vol_spprb
            ORDER BY
                a.no_npp ASC";
        
        $query = DB::select($raw_query);
        // $query = MonOp::with('produk', 'npp', 'vSpprbRi')
        //     ->with([
        //         'vSpprbRi.sptbh'=> function($sql) use($request) {
        //             $sql->where('jns_sptb', '0');
        //             $sql->with('sptbd');
        //         }
        //     ])
        //     ->whereHas('vSpprbRi.sptbh', function($sql){
        //         $sql->where('jns_sptb', '0');
        //     })
        //     ->whereNoNpp('201A0576BL');

        

        // if($request->vendor_id){
        //     $query->where('sp3_h.vendor_id', $request->vendor_id);
        // }

        // if($request->periode){
        //     $periode = explode(' - ', $request->periode);

        //     $periode[0] = date('Y-m-d', strtotime($periode[0]));
        //     $periode[1] = date('Y-m-d', strtotime($periode[1]));

        //     $query->whereBetween('sp3_h.tgl_sp3', $periode);
        // }
        // $query->whereBetween('sp3_h.tgl_sp3', ['2022-01-01', '2022-09-31']);
        // if($request->tahun2 && $request->tahun2 != ''){
        //     if($request->range == 'sd'){
        //         $awal = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AWAL_THN\" ('" . $request->tahun2 . "') tgl FROM dual")[0]->tgl);
        //         $akhir = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AKHIR_BLN\" ('" . $request->tahun2 . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
        //     }else{
        //         $awal = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AWAL_BLN\" ('" . $request->tahun2 . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
        //         $akhir = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AKHIR_BLN\" ('" . $request->tahun2 . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
        //     }
        //     $query->whereBetween('sp3_h.tgl_sp3', [date('Y-m-d', strtotime($awal)), date('Y-m-d', strtotime($akhir))]);
        // }

        return DataTables::of($query)
            
            // ->editColumn('no_sp3', function ($model) {
            //     return $model->no_sp3 . '<br>' . $model->no_npp . ' - ' . ($model->npp->nama_proyek ?? '');
            // })
            // ->editColumn('tgl_sptb', function ($model) {
            //     return $model->tgl_sptb ? Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_sptb)->format('d-m-Y') : '-';
            // })
            ->rawColumns([''])
            ->toJson();
    }

    public function dataVendorSemester(Request $request)
    {
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $query = Sp3::with('vendor', 'sp3D', 'npp', 'sptbh_by_npp')
            ->with([
                'sptbh'=> function($sql) use($request) {
                    $sql->with('sptbd', 'sptbd2', 'spmh');
                }
            ])
            ->whereNotNull('sp3_h.vendor_id')
            ->withSum('sp3D', 'vol_akhir')
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_h.no_sp3', '=', 'last_sp3.no_sp3');
            })->select('sp3_h.*');

        if($request->kd_pat){
            $query->where('sp3_h.kd_pat', $request->kd_pat);
        }

        if($request->vendor_id){
            $query->where('sp3_h.vendor_id', $request->vendor_id);
        }

        if($request->minggu1){
            $minggu1 = explode(';', $request->minggu1);
            $minggu2 = explode(';', $request->minggu2);

            $periode[0] = date('Y-m-d', strtotime($minggu1[0]));
            $periode[1] = date('Y-m-d', strtotime($minggu2[1]));

            $query->whereBetween('sp3_h.tgl_sp3', $periode);
        }

        $data1 = $query->get()->groupBy('vendor_id');
        $data = $data1->map(function ($items, $key) {
                $mutu = $items->avg(function($item){
                    $v = $item->sp3D->sum('vol_akhir');
                    $vr = $item->sptbh->filter(function($itm) use ($item) {
                        return ($itm->spmh->vendor_id ?? '') == $item->vendor_id;
                    })->sum(function($item){
                        return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                    });
                    return $v == 0 ? 0 : round(($v - $vr) / $v * 100, 2);
                });
                $waktu = $items->avg(function($item){
                    $v = $item->sp3D->sum('vol_akhir');
                    $late = $item->sptbh->filter(function($itm) use ($item) {
                        return ($itm->spmh->vendor_id ?? '') == $item->vendor_id;
                    })->filter(function($item1){ 
                        return strtotime($item1->tgl_sptb) > strtotime($item1->spmh->tgl_spm); 
                    })->sum(function($item){
                        return $item->sptbd2->sum('vol');
                    });
                    $wkt = $v == 0 ? 0 : round(($v - $late) / $v * 100, 2);
                    $nilai = 0;
                    if($wkt < 50){
                        $nilai = 50;
                    }elseif($wkt > 70){
                        $nilai = 90;
                    }else{
                        $nilai = 70;
                    }
                    return $nilai;
                });
                $pelayanan = $items->avg(function($item){
                    return $item->sptbh->filter(function($itm) use ($item) {
                        return ($itm->spmh->vendor_id ?? '') == $item->vendor_id;
                    })->avg(function($item){
                        if($item->nilai_pelayanan == null){
                            return 0;
                        }else{
                            return $item->nilai_pelayanan;
                        }
                    });
                });
                $k3l = $items->avg(function($item_){
                    $nilai = $item_->sptbh->filter(function($itm) use ($item_) {
                        return ($itm->spmh->vendor_id ?? '') == $item_->vendor_id;
                    })->filter(function($item){ 
                        return $item->spmh->armada_rating != null; 
                    })->avg(function($item){
                        return $item->spmh->armada_rating->details->sum('bobot');
                    });
                    return $nilai ;
                });
                $total = ($mutu * 35 / 100) + ($waktu * 35 / 100) + ($pelayanan * 20 / 100) + ($k3l * 10 / 100);
                return [
                    'no_sp3' => $items[0]['no_sp3'],
                    'alamat_vendor' => $items[0]['alamat_vendor'],
                    'vendor' => $items[0]['vendor']['nama'] ?? '',
                    'mutu' => $mutu,
                    'waktu' => $waktu,
                    'pelayanan' => $pelayanan,
                    'k3l' => $k3l,
                    'total' => $total,
                ];
            })
            ->sortByDesc('total');

        // return response()->json($data);
        return view('pages.report.evaluasi-vendor.table-data.semester',[
            'data' => $data
        ]);
    }

}
