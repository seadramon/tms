<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
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

class EvaluasiVendorController extends Controller
{
    public function index(){
        $labelSemua = ["" => "Semua"];

        $kd_pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $kd_pat = $labelSemua + $kd_pat;

        if(Auth::check()){
            $vendor = Vendor::where('vendor_id', Auth::user()->vendor_id)->get()->pluck('nama', 'vendor_id')->toArray();
            $vendor_id = $vendor;
        }else{
            $vendor = Vendor::get()->pluck('nama', 'vendor_id')->toArray();
            $vendor_id = $vendor;
        }

        $tipe = [
            'bulanan' => 'Bulanan',
            'semester' => 'Semester'
        ];
        $pekerjaan = [
            'darat' => 'Angkutan Darat'
        ];

        return view('pages.report.evaluasi-vendor.index', compact(
            'kd_pat',
            'vendor_id',
            'tipe',
            'pekerjaan'
        ));
    }

    public function data(Request $request)
    {
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $query = Sp3::with('vendor', 'sp3D', 'npp', 'sptbh_by_npp')
            ->with([
                'sptbh'=> function($sql) use($request) {
                    $sql->whereHas('spmh', function($sql1) use($request) {
                        $sql1->where('vendor_id', $request->vendor_id);
                    });
                    $sql->with('sptbd', 'sptbd2', 'spmh');
                }
            ])
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

        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

            $query->whereBetween('sp3_h.tgl_sp3', $periode);
        }

        return DataTables::eloquent($query->orderBy('tgl_sp3', 'asc'))
            ->addColumn('bulan', function ($model) {
                return date('M', strtotime($model->tgl_sp3));
            })
            ->addColumn('volume', function ($model) {
                return $model->sp3D->sum('vol_akhir');
            })
            ->addColumn('volume_diterima', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $vr = $model->sptbh->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
                return $v - $vr;
            })
            ->addColumn('volume_rusak', function ($model) {
                return $model->sptbh->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
            })
            ->addColumn('nilai_mutu', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $vr = $model->sptbh->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
                return round(($v - $vr) / $v * 100, 2);
            })
            ->addColumn('terlambat', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $late = $model->sptbh->filter(function($item1){ 
                    return strtotime($item1->tgl_sptb) > strtotime($item1->spmh->tgl_spm); 
                })->sum(function($item){
                    return $item->sptbd2->sum('vol');
                });
                return round($late / $v * 100, 2) . '%';
            })
            ->addColumn('tepat_waktu', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $late = $model->sptbh->filter(function($item1){ 
                    return strtotime($item1->tgl_sptb) > strtotime($item1->spmh->tgl_spm); 
                })->sum(function($item){
                    return $item->sptbd2->sum('vol');
                });
                return round(($v - $late) / $v * 100, 2) . '%';
            })
            ->editColumn('tgl_sp3', function ($model) {
                return date('Ymd', strtotime($model->tgl_sp3));
            })
            ->editColumn('no_sp3', function ($model) {
                return $model->no_sp3 . '<br>' . $model->no_npp . ' - ' . ($model->npp->nama_proyek ?? '');
            })
            // ->editColumn('tgl_sptb', function ($model) {
            //     return $model->tgl_sptb ? Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_sptb)->format('d-m-Y') : '-';
            // })
            ->rawColumns(['no_sp3'])
            ->toJson();
    }

    public function chart(Request $request)
    {
        $baseQuery = SpmH::leftJoin('sptb_h', 'sptb_h.no_spm', '=', 'spm_h.no_spm')
            ->leftJoin('tms_armadas', 'tms_armadas.nopol', '=', 'spm_h.no_pol');

        if($request->tipe == 'bulanan'){
            $baseQuery->select(DB::raw("EXTRACT(YEAR from tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0') as thbl"), DB::raw('count(*) as total'))
                ->groupby(DB::raw("EXTRACT(YEAR from tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0')"))
                ->orderBy(DB::raw("EXTRACT(YEAR FROM tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0')"));
        }else{
            $baseQuery->select(DB::raw("EXTRACT(YEAR from tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0') || '-' || LPAD(EXTRACT(DAY from tgl_spm), 2, '0') as thbl"), DB::raw('count(*) as total'))
                ->groupby(DB::raw("EXTRACT(YEAR from tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0') || '-' || LPAD(EXTRACT(DAY from tgl_spm), 2, '0')"))
                ->orderBy(DB::raw("EXTRACT(YEAR FROM tgl_spm) || '-' || LPAD(EXTRACT(MONTH FROM tgl_spm), 2, '0') || '-' || LPAD(EXTRACT(DAY FROM tgl_spm), 2, '0')"));
        }

        if($request->kd_pat){
            $baseQuery->whereHas('sppb.npp', function($sql) use($request) {
                $sql->where('kd_pat', $request->kd_pat);
            });
        }

        if($request->pbb_muat){
            $baseQuery->where('sptb_h.kd_pat', $request->pbb_muat);
        }

        if($request->vendor_id){
            $baseQuery->where('spm_h.vendor_id', $request->vendor_id);
        }

        if($request->kd_material){
            $baseQuery->where('tms_armadas.kd_armada', $request->kd_material);
        }

        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

            $baseQuery->whereBetween('spm_h.tgl_spm', $periode);
        }

        $realisasi = clone $baseQuery;
        
        $realisasi = $realisasi->whereHas('sptbh')->get();
        
        $rencana = $baseQuery->get();

        $listBulan = getListBulan();

        $kategori = [];
        $totalRencana = [];
        $totalRealisasi = [];
        $listRealisasi = [];

        foreach ($realisasi as $real) {
            $listRealisasi[$real->thbl] = [
                'value' => $real->total
            ];
        }

        foreach ($rencana as $renc) {
            $thbl = explode('-', $renc->thbl);

            if($request->tipe == 'bulanan'){
                $kategori[] = [
                    'label' => $listBulan[((int)$thbl[1])-1] . substr($thbl[0], -2)
                ];
            }else{
                $kategori[] = [
                    'label' => $thbl[2] . $listBulan[((int)$thbl[1])-1] . substr($thbl[0], -2)
                ];
            }

            $totalRencana[] = [
                'value' => $renc->total
            ];

            $totalRealisasi[] = [
                'value' => array_key_exists($renc->thbl, $listRealisasi) ? $listRealisasi[$renc->thbl]['value'] : 0
            ];
        }
        
        return [
            'kategori'  => $kategori,
            'rencana'   => $totalRencana,
            'realisasi' => $totalRealisasi
        ];
    }

    public function boxData(Request $request)
    {
        $baseQuery = SpmH::leftJoin('sptb_h', 'sptb_h.no_spm', '=', 'spm_h.no_spm')
            ->leftJoin('tms_armadas', 'tms_armadas.nopol', '=', 'spm_h.no_pol')
            ->leftJoin('spm_d', 'spm_d.no_spm', '=', 'spm_h.no_spm')
            ->leftJoin('vendor', 'vendor.vendor_id', '=', 'spm_h.vendor_id')
            ->whereNotNull('spm_d.vol');

        if($request->kd_pat){
            $baseQuery->whereHas('sppb.npp', function($sql) use($request) {
                $sql->where('kd_pat', $request->kd_pat);
            });
        }

        if($request->pbb_muat){
            $baseQuery->where('sptb_h.kd_pat', $request->pbb_muat);
        }

        if($request->vendor_id){
            $baseQuery->where('spm_h.vendor_id', $request->vendor_id);
        }

        if($request->kd_material){
            $baseQuery->where('tms_armadas.kd_armada', $request->kd_material);
        }

        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

            $baseQuery->whereBetween('spm_h.tgl_spm', $periode);
        }

        $box1 = clone $baseQuery;
        $box1 = $box1->select(DB::raw("spm_h.vendor_id"), DB::raw("vendor.nama"), DB::raw('sum(spm_d.vol) as total'))
            ->groupby(DB::raw("spm_h.vendor_id"), DB::raw("vendor.nama"))
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $box2Sptb = clone $baseQuery;
        $box2Sptb = $box2Sptb->whereHas('sptbh')->count();

        $box2Spm = clone $baseQuery;
        $box2Spm = $box2Spm->count();

        $box2 = number_format(($box2Sptb / $box2Spm * 100), 2, ',', '');

        $box3Dipercepat1 = clone $baseQuery;
        $box3Dipercepat1 = $box3Dipercepat1->select(DB::raw('sum(spm_d.vol) as total'))
            ->whereHas('sptbh', function($query){
                $query->where('sptb_h.tgl_sptb', '<', DB::raw('spm_h.tgl_spm'));
            })->first()->total ?? 0;

        $box3Dipercepat2 = clone $baseQuery;
        $box3Dipercepat2 = $box3Dipercepat2->select(DB::raw('sum(spm_d.vol) as total'))
            ->first()->total ?? 0;

        $box3Dipercepat = number_format(($box3Dipercepat1 / $box3Dipercepat2 * 100), 2, ',', '');

        $box3TepatWaktu1 = clone $baseQuery;
        $box3TepatWaktu1 = $box3TepatWaktu1->select(DB::raw('sum(spm_d.vol) as total'))
            ->whereHas('sptbh', function($query){
                $query->where('sptb_h.tgl_sptb', '=', DB::raw('spm_h.tgl_spm'));
            })->first()->total ?? 0;

        $box3TepatWaktu2 = clone $baseQuery;
        $box3TepatWaktu2 = $box3TepatWaktu2->select(DB::raw('sum(spm_d.vol) as total'))
            ->first()->total ?? 0;

        $box3TepatWaktu = number_format(($box3TepatWaktu1 / $box3TepatWaktu2 * 100), 2, ',', '');

        $box3Terlambat1 = clone $baseQuery;
        $box3Terlambat1 = $box3Terlambat1->select(DB::raw('sum(spm_d.vol) as total'))
            ->whereHas('sptbh', function($query){
                $query->where('sptb_h.tgl_sptb', '>', DB::raw('spm_h.tgl_spm'));
            })->first()->total ?? 0;

        $box3Terlambat2 = clone $baseQuery;
        $box3Terlambat2 = $box3Terlambat2->select(DB::raw('sum(spm_d.vol) as total'))
            ->first()->total ?? 0;

        $box3Terlambat = number_format(($box3Terlambat1 / $box3Terlambat2 * 100), 2, ',', '');
        
        return [
            'box1'  => $box1,
            'box2'  => [$box2, $box2Sptb, $box2Spm],
            'box3'  => [$box3Dipercepat, $box3TepatWaktu, $box3Terlambat]
        ];
    }
}
