<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisPekerjaan;
use App\Models\MsNoDokumen;
use App\Models\Npp;
use App\Models\Pat;
use App\Models\Personal;
use App\Models\Sp3;
use App\Models\SptbD;
use App\Models\Vendor;
use App\Models\Spk;
use App\Models\SpkD;
use App\Models\SpkPasal;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpkExport;
use App\Models\SpkPic;
use Illuminate\Support\Str;

class BappController extends Controller
{
    public function index(){
        // return response()->json(json_decode(session('TMS_ACTION_MENU')));
        $labelSemua = ["" => "Semua"];

        if(!Auth::check() && session('TMP_KDWIL') != '0A'){
            $pat = Pat::where('kd_pat', session('TMP_KDWIL'))->get()->pluck('ket', 'kd_pat')->toArray();
		}else{
            $pat = Pat::all()->pluck('ket', 'kd_pat')->toArray();
            $pat = $labelSemua + $pat;
        }

        $muat = Pat::where('kd_pat', 'like', '2%')->get()->pluck('ket', 'kd_pat')->toArray();
        $muat = $labelSemua + $muat;

        $periode = [];

        for($i=0; $i<10; $i++){
            $year = date('Y', strtotime('-' . $i . ' years'));
            $periode[$year] = $year;
        }

        $periode = $labelSemua + $periode;

        $status = [
            ''                  => 'Semua',
            'belum_verifikasi'  => 'Belum Verifikasi',
            'aktif'             => 'Aktif',
            'selesai'           => 'Selesai'
        ];

        $rangeCutOff = [
            'sd'    => 's/d',
            'di'    => '='
        ];

        $monthCutOff = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $jenisPekerjaan = JenisPekerjaan::get()
            ->pluck('ket', 'kd_jpekerjaan')
            ->toArray();

        $jenisPekerjaan = ["" => "Semua"] + $jenisPekerjaan;

        return view('pages.spk.index', compact(
            'pat', 'periode', 'status', 'rangeCutOff', 'monthCutOff', 'muat', 'jenisPekerjaan'
        ));
    }

    public function data(Request $request)
    {
        //
        $query = Spk::with('vendor', 'spk_d', 'unitkerja')
            ->select('spk_h.no_spk', 'spk_h.tgl_spk', 'spk_h.app1', 'spk_h.app2', 'spk_h.no_npp', 'spk_h.vendor_id', 'spk_h.kd_pat', 'spk_h.jadwal1', 'spk_h.jadwal2', 'spk_h.kd_jpekerjaan');

        if($request->pat){
            $query->where('kd_pat', $request->pat);
        }
        if($request->ppb_muat){
            $query->whereHas('spk_d', function($sql) use ($request){
                $sql->where('pat_to', $request->ppb_muat);
            });
        }
        if($request->periode && $request->periode != ''){
            if($request->range == 'sd'){
                $awal = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AWAL_THN\" ('" . $request->periode . "') tgl FROM dual")[0]->tgl);
                $akhir = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AKHIR_BLN\" ('" . $request->periode . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
            }else{
                $awal = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AWAL_BLN\" ('" . $request->periode . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
                $akhir = str_replace('.', '-', DB::select("select WOS.\"FNC_GET_TGL_AKHIR_BLN\" ('" . $request->periode . "', '" . $request->month . "') tgl FROM dual")[0]->tgl);
            }
            $query->whereBetween('spk_h.tgl_spk', [date('Y-m-d 00:00:00', strtotime($awal)), date('Y-m-d 23:59:59', strtotime($akhir))]);
        }
        if($request->pekerjaan){
            $query->where('kd_jpekerjaan', $request->pekerjaan);
        }
        // if($request->status){
        //     if($request->status == 'aktif'){
        //         $query->where('app1', 1)->whereRaw('v_sp3_ri.vol_sp3 > v_sp3_ri.vol_sptb');
        //     }elseif ($request->status == 'selesai') {
        //         $query->where('app1', 1)->whereRaw('v_sp3_ri.vol_sp3 <= v_sp3_ri.vol_sptb');
        //     }elseif ($request->status == 'belum_verifikasi') {
        //         $query->where('app1', '<>', 1);
        //     }
        // }

        if(Auth::check()){
            $query->where('spk_h.vendor_id', Auth::user()->vendor_id)->where('app1', 1);
        }

        return DataTables::eloquent($query)
                ->editColumn('tgl_spk', function ($model) {
                    return date('d-m-Y', strtotime($model->tgl_spk));
                })
                ->addColumn('approval', function ($model) {
                    $teks = '';
                    if(Auth::check()){
                        if($model->app2 == 1){
                            $teks .= '<span class="badge badge-light-success mr-2 mb-2">Confirmed&nbsp;<i class="fas fa-check text-success"></i></span>';
                        }else{
                            $teks .= '<span class="badge badge-light-warning mr-2 mb-2">To Be Confirmed</i></span>';
                        }
                    }else{
                        if($model->app1 == 1){
                            $teks .= '<span class="badge badge-light-success mr-2 mb-2">MUnit&nbsp;<i class="fas fa-check text-success"></i></span>';
                        }
                        if($model->app2 == 1){
                            $teks .= '<span class="badge badge-light-success mr-2 mb-2">Vendor&nbsp;<i class="fas fa-check text-success"></i></span>';
                        }
                    }
                    return $teks;
                })
                ->addColumn('custom', function ($model) {
                    if(Auth::check()){
                        return $model->unitkerja->ket ?? '-';
                    }else{
                        return $model->vendor->nama ?? '-';
                    }
                })
                ->addColumn('progress_vol', function ($model) {
                    $vol_sptb = SptbD::whereHas('sptbh',function($sql) use ($model) {
                        $sql->where('no_npp', $model->no_npp);
                        $sql->whereHas('spmh',function($sql) use ($model) {
                            $sql->where('vendor_id', $model->vendor_id);
                        });
                    })->sum('vol');
                    $vol_sp3 = $model->spk_d->sum('vol_akhir');
                    $vol = $vol_sp3 == 0 ? 0 : round($vol_sptb / $vol_sp3 * 100, 2);
                    if($vol >= 100){
                        $vol = 100;
                        $badge = 'success';
                    }elseif($vol >= 75){
                        $badge = 'warning';
                    }else{
                        $badge = 'dark';
                    }
                    return '<span class="badge badge-square badge-' . $badge . ' me-10 mb-10 badge-outline">' . $vol . '%</span>';
                })
                ->addColumn('progress_rp', function ($model) {
                    $sp3d = $model->spk_d->groupBy(function($item){ return $item->kd_produk . '_' . $item->pat_to; });
                    $vol_sptb = SptbD::with('sptbh')->whereHas('sptbh',function($sql) use ($model) {
                        $sql->where('no_npp', $model->no_npp);
                        $sql->whereHas('spmh',function($sql) use ($model) {
                            $sql->where('vendor_id', $model->vendor_id);
                        });
                    })
                    ->get()
                    ->sum(function($item) use($sp3d) {
                        $key = $item->kd_produk . '_' . $item->sptbh->kd_pat;
                        return $item->vol * ($sp3d[$key][0]->harsat_akhir ?? 0);
                    });
                    $vol_sp3 = $model->spk_d->sum(function($item) { return intval($item->vol_akhir) * intval($item->harsat_akhir); });
                    $vol = $vol_sp3 == 0 ? 0 : round($vol_sptb / $vol_sp3 * 100, 2);
                    if($vol >= 100){
                        $vol = 100;
                        $badge = 'success';
                    }elseif($vol >= 75){
                        $badge = 'warning';
                    }else{
                        $badge = 'dark';
                    }
                    return '<span class="badge badge-square badge-' . $badge . ' me-10 mb-10 badge-outline">' . $vol . '%</span>';
                })
                ->addColumn('progress_wkt', function ($model) {
                    $ret = 0;
                    if (!is_null($model->jadwal1) && !is_null($model->jadwal2)) {
                        $a = differenceDate($model->jadwal1, date('Y-m-d'));
                        $b = differenceDate($model->jadwal1, $model->jadwal2);

                        if ($b > 0) {
                            $ret = round(($a / $b) * 100, 2);
                        }
                    }
                    if($ret > 100){
                        $ret = 100;
                    }
                    if($ret < 0){
                        $ret = 0;
                    }

                    if($ret >= 100){
                        $ret = 100;
                        $badge = 'success';
                    }elseif($ret >= 75){
                        $badge = 'warning';
                    }else{
                        $badge = 'dark';
                    }
                    return '<span class="badge badge-square badge-' . $badge . ' me-10 mb-10 badge-outline">' . $ret . '%</span>';
                })
                ->addColumn('menu', function ($model) {
                    $list = '';
                    $list .= '<li><a class="dropdown-item" href="' . route('spk.show', str_replace('/', '|', $model->no_spk)) . '">View</a></li>';
                    $list .= '<li><a class="dropdown-item" href="' . route('spk.edit', str_replace('/', '|', $model->no_spk)) . '">Edit</a></li>';
                    $list .= '<li><a class="dropdown-item" href="' . route('spk.print-pdf', str_replace('/', '|', $model->no_spk)) . '">Print PDF</a></li>';
                    /*$list .= '<li><a class="dropdown-item" href="' . route('spk.print-excel', str_replace('/', '|', $model->no_spk)) . '">Print Excel</a></li>';*/
                    // if(Auth::check()){
                    //     $list .= '<li><a class="dropdown-item" href="'.route('sp3.print', str_replace('/', '|', $model->no_sp3)).'">Print</a></li>';
                    //     $list .= '<li><a class="dropdown-item" href="' . url('sp3', str_replace('/', '|', $model->no_sp3)) . '">View</a></li>';
                    //     if($model->app1 == 1 && in_array($model->app2, [null, 0])){
                    //         $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['second', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                    //     }
                    // }else{
                    //     $action = json_decode(session('TMS_ACTION_MENU'));
                    //     if(in_array('view', $action)){
                    //         $list .= '<li><a class="dropdown-item" href="' . url('sp3', str_replace('/', '|', $model->no_sp3)) . '">View</a></li>';
                    //     }
                    //     if(in_array('edit', $action) && $model->app1 != 1){
                    //         if($model->kd_jpekerjaan == '20'){
                    //             $route = 'sp3.v2.edit';
                    //         }else{
                    //             $route = 'sp3.edit';
                    //         }
                    //         $list .= '<li><a class="dropdown-item" href="' . route($route, str_replace('/', '|', $model->no_sp3)) . '">Edit</a></li>';
                    //     }
                    //     if(in_array('amandemen', $action) && $model->app1 == 1){
                    //         $list .= '<li><a class="dropdown-item" href="' . route('sp3.amandemen', str_replace('/', '|', $model->no_sp3)) . '">Amandemen</a></li>';
                    //     }
                    //     if(in_array('print', $action)){
                    //         $list .= '<li><a class="dropdown-item" href="'.route('sp3.print', str_replace('/', '|', $model->no_sp3)).'">Print</a></li>';
                    //     }
                    //     if(in_array('approve1', $action) && $model->app1 == 0){
                    //         $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['first', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                    //     }
                    //     if(in_array('approve2', $action) && $model->app1 == 1){
                    //         $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['second', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                    //     }
                    // }
                    $edit = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                ' . $list . '
                            </ul>
                            </div>';

                    return $edit;
                })
                ->rawColumns(['menu', 'approval', 'progress_vol', 'progress_rp', 'progress_wkt', 'custom'])
                ->toJson();
    }

    public function create(Request $request)
    {
        $sp3 = Sp3::where('vendor_id', 'WBI075')
            ->whereNotNull('no_npp')
            ->get()
            ->mapWithKeys(function($item){
                return [$item->no_sp3 => $item->no_sp3];
            })
            ->all();

        $sp3 = ["" => "Pilih No SP3"] + $sp3;

        return view('pages.bapp.create', [
            'sp3' => $sp3,
            'mode' => "create"
        ]);
    }

    public function fetchFromSp3(Request $request)
    {
        $code = 200;
        try {
            $sp3 = Sp3::with('pic', 'sp3D.produk')->find($request->sp3);
            $npp = Npp::with(['infoPasar.region'])
                ->where('no_npp', $sp3->no_npp)
                ->first();

            $persons = Personal::with('jabatan')
                ->whereIn('employee_id', $sp3->pic->map(function($item){ return $item->employee_id; })->all())
                ->get();

            $personal = $persons->mapWithKeys(function($item){
                    return [$item->employee_id => $item->full_name];
                })
                ->all();
            $personal = ["" => "---Pilih---"] + $personal;
            $opt_personal = $persons->mapWithKeys(function($item){
                return [$item->employee_id => ['data-jabatan' => ($item->jabatan->ket ?? '-')]];
            })
            ->all();

            $trader = DB::connection('oracle-eproc')
                        ->table(DB::raw('"m_trader"'))
                        ->where('vendor_id', $sp3->vendor_id)
                        ->first();
            $html = view('pages.bapp.create_form', [
                'sp3' => $sp3,
                'npp' => $npp,
                'personal' => $personal,
                'opt_personal' => $opt_personal,
                'trader' => $trader,
                'mode' => $request->mode,
            ])->render();
            $result = array('success' => true, 'html'=> $html);

        } catch(Exception $e) {
            $code = 400;
            $result = array('success' => false, 'message'=> $e->getMessage());
        }

        return response()->json($result, $code);
    }

    public function fetchTerbilang(Request $request)
    {
        $code = 200;
        $result = array('success' => true, 'terbilang'=> Str::title(penyebut($request->nilai)));

        return response()->json($result, $code);
    }

}
