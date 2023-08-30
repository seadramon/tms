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
            $vendor_id = $labelSemua + $vendor;
        }else{
            $vendor = Vendor::get()->pluck('nama', 'vendor_id')->toArray();
            $vendor_id = $labelSemua + $vendor;
        }

        $tipe = [
            'sp3' => 'Sp3 Bulanan',
            'bulanan' => 'Vendor Bulanan',
            'semester' => 'Vendor Semester'
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

    public function dataSp3(Request $request)
    {
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $query = Sp3::with('vendor', 'sp3D', 'npp', 'sptbh_by_npp')
            ->with([
                'sptbh'=> function($sql) use($request) {
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
                $vr = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
                return $v - $vr;
            })
            ->addColumn('volume_rusak', function ($model) {
                return $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
            })
            ->addColumn('nilai_mutu', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $vr = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->sum(function($item){
                    return $item->sptbd2->filter(function($item1){ return $item1->kondisi_produk == 'rusak'; })->sum('vol');
                });
                return $v == 0 ? 0 : round(($v - $vr) / $v * 100, 2);
            })
            ->addColumn('terlambat', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $late = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->filter(function($item1){ 
                    return strtotime($item1->tgl_sptb) > strtotime($item1->spmh->tgl_spm); 
                })->sum(function($item){
                    return $item->sptbd2->sum('vol');
                });
                return $v == 0 ? 0 : round($late / $v * 100, 2) . '%';
            })
            ->addColumn('tepat_waktu', function ($model) {
                $v = $model->sp3D->sum('vol_akhir');
                $late = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->filter(function($item1){ 
                    return strtotime($item1->tgl_sptb) > strtotime($item1->spmh->tgl_spm); 
                })->sum(function($item){
                    return $item->sptbd2->sum('vol');
                });
                return $v == 0 ? 0 : round(($v - $late) / $v * 100, 2) . '%';
            })
            ->addColumn('aspek_pelayanan', function ($model) {
                $nilai = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->avg(function($item){
                    if($item->nilai_pelayanan == null){
                        return 0;
                    }else{
                        return $item->nilai_pelayanan;
                    }
                });
                return $nilai;
            })
            ->addColumn('aspek_k3l', function ($model) {
                $nilai = $model->sptbh->filter(function($itm) use ($model) {
                    return ($itm->spmh->vendor_id ?? '') == $model->vendor_id;
                })->filter(function($item){ 
                    return $item->spmh->armada_rating != null; 
                })->avg(function($item){
                    return $item->spmh->armada_rating->details->sum('bobot');
                });
                return $nilai ?? 0;
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

        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

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
