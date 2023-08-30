<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Sp3;
use App\Models\SptbD;
use App\Models\MonOp;
use App\Models\Sp3D;
use App\Models\Npp;
use App\Models\PotensiH;
use App\Models\Views\VPotensiMuat;
use App\Models\Views\VSpprbRi as ViewsVSpprbRi;
use App\Models\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Log;
 use Carbon\Carbon;

class SppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.spp.index');
    }

    public function data()
    {
        $query = SppbH::with(['spprb', 'detail'])->select('*');
        if(!Auth::check() && session('TMP_KDWIL') != '0A'){
			$query->whereHas('npp', function($sql){
                $sql->where('kd_pat', session('TMP_KDWIL'));
            });
		}

        return DataTables::eloquent($query)
            ->editColumn('jadwal1', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal1));
            })
            ->editColumn('jadwal2', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal2));
            })
            ->addColumn('no_sp3', function($model) {
                $nosp3 = "";

                if (!empty($model->spprb)) {
                    $temp = Sp3::where('no_npp', $model->spprb->no_npp)->first();

                    if ($temp) {
                        $nosp3 = $temp->no_sp3;
                    }
                }

                return $nosp3;
            })
            ->addColumn('waktu', function($model) {
                $ret = 0;
                if (!empty($model->jadwal1) && !empty($model->jadwal2)) {
                    $a = $this->diffDate($model->jadwal1, date('Y-m-d'));
                    $b = $this->diffDate($model->jadwal1, $model->jadwal2);

                    if ($b > 0) {
                        $ret = round(($a / $b) * 100);
                    }
                }
                if($ret > 100){
                    $ret = 100;
                }
                if($ret < 0){
                    $ret = 0;
                }

                return $ret . '%';
            })
            ->addColumn('vol', function($model) {
                $res = "";

                $sptb = SptbD::where('no_spprb', $model->no_spprb)->sum('vol');
                $sppb = $model->detail->sum('vol');

                if ($sppb > 0) {
                    $res = round($sptb / $sppb);
                }

                return $res . '%';
            })
            ->addColumn('approval', function ($model) {
                $teks = '';
                if($model->app == 1){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">KSDM&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                if($model->app2 == 1){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">PEO&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                if($model->app3 == 1){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">MUnit&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                return $teks;
            })
            ->addColumn('menu', function ($model) {
                $noSppb = str_replace("/", "|", $model->no_sppb);
                $sumVolApp2 = $model->detail->sum('app2_vol');
                $approval = "";
                $action = json_decode(session('TMS_ACTION_MENU'));
                switch (true) {
                    case ($model->app == 0 && in_array('approve1', $action)):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'first',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve KSDM";
                        break;
                    case ($model->app == 1 && $model->app2 == 0 && in_array('approve2', $action)):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'second',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve PEO";
                        break;
                    case ($model->app == 1 && $model->app2 == 1 && $model->app3 == 0 && $sumVolApp2 == 0 && in_array('approve3', $action)):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'third',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve MUnit";
                        break;
                    default:
                        $approve = "";
                        $caption = "";
                        break;
                }
                if ($approve!="") {
                    $approval = '<li><a class="dropdown-item" href="'.$approve.'">'. $caption .'</a></li>';
                }
                $list = '';
                if(Auth::check()){
                    
                }else{
                    if(in_array('view', $action)){
                        $list .= '<li><a class="dropdown-item" href="'. route('spp.show', ['spp' => $noSppb]) .'">View</a></li>';
                    }
                    if($model->app == 0 && in_array('edit', $action)){
                        $list .= '<li><a class="dropdown-item" href="'. route('spp.edit', ['spp' => $noSppb]) .'">Edit</a></li>';
                    }
                    if(in_array('amandemen', $action)){
                        $list .= '<li><a class="dropdown-item" href="'. route('spp.amandemen', ['spp' => $noSppb]) .'">Amandemen</a></li>';
                    }
                    if(in_array('print', $action)){
                        $list .= '<li><a class="dropdown-item" href="'. route('spp.print', ['spp' => $noSppb]) .'">Print</a></li>';
                    }
                }
                $list .= $approval;

                $edit = '<div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            ' . $list . '
                            <li><a class="dropdown-item" href="#">Hapus</a></li>
                        </ul>
                        </div>';

                return $edit;
            })
            ->rawColumns(['menu', 'waktu', 'approval'])
            ->toJson();
    }

    public function dataSpprb(Request $request)
    {
        $noNpp = !empty($request->no_npp)?$request->no_npp:null;
        
        $query = VSpprbRi::with(['produk', 'pat'])
        ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
        ->select('v_spprb_ri.pat_to', 'v_spprb_ri.spprblast', 'v_spprb_ri.kd_produk', 'v_spprb_ri.vol_spprb', 'spprb_h.jadwal1',
            'spprb_h.jadwal2');

        if (!empty($noNpp)) {
            $query->where('v_spprb_ri.no_npp', $noNpp);
        }

        return DataTables::eloquent($query)
            ->editColumn('jadwal1', function ($model) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $model->jadwal1)->format('d-m-Y');
            })
            ->editColumn('jadwal2', function ($model) {
                return $model->jadwal2 ? Carbon::createFromFormat('Y-m-d H:i:s', $model->jadwal2)->format('d-m-Y') : '-';
            })
            ->toJson();
    }

    public function dataAngkutan(Request $request)
    {
        $noSppb = !empty($request->noSppb)?$request->noSppb:null;

        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $query = SppbH::join('spprb_h', 'spprb_h.no_spprb', '=', 'sppb_h.no_spprb')
            ->join('sp3_h', 'sp3_h.no_npp', '=', 'spprb_h.no_npp')
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_h.no_sp3', '=', 'last_sp3.no_sp3');
            })
            ->join('vendor', 'vendor.vendor_id', '=', 'sp3_h.vendor_id')
            ->where('sppb_h.no_sppb', $noSppb)
            ->select('spprb_h.no_spprb', 'sp3_h.no_sp3', 'sp3_h.app1', 'sp3_h.app2', 'sp3_h.st_wf', 'vendor.nama as vendorname', DB::raw("(SELECT sum(vol_akhir) FROM sp3_d WHERE NO_SP3 = sp3_h.NO_SP3) AS volakhir"), DB::raw("(SELECT sum(VOL_TON_AKHIR) FROM sp3_d WHERE NO_SP3 = sp3_h.NO_SP3) AS voltonakhir"));

        return DataTables::eloquent($query)
            ->editColumn('volakhir', function ($model) {
                return number_format($model->volakhir, 2);
            })
            ->editColumn('voltonakhir', function ($model) {
                return number_format($model->voltonakhir, 2);
            })
            ->addColumn('status', function ($model) {
                $teks = '';
                if($model->app1 == 1){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">MUnit&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                if($model->app2 == 1){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">MDiv&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                return $teks;
            })
            // ->addColumn('status', function ($model) {
            //     $html = "";
            //     switch (true) {
            //         case $model->st_wf == 0:
            //             $a = '<i class="fa fa-square" style="color:yellow; font-size:20px;"></i>';
            //             break;
            //         case $model->st_wf == 1 && $model->app1 == 0:
            //             $a = '<i class="fa fa-square" style="color:orange; font-size:20px;"></i>';
            //             break;
            //         case $model->st_wf == 1 && $model->app1 == 1:
            //             $a = '<i class="fa fa-square" style="color:green; font-size:20px;"></i>';
            //             break;
                    
            //     }

            //     if ($model->app2 == 1) {
            //         $b = '<i class="fa fa-square" style="color:green; font-size:20px;"></i>';
            //     } else {
            //         $b = '<i class="fa fa-square" style="color:grey; font-size:20px;"></i>';
            //     }

            //     return $a.'&nbsp;'.$b;
            // })
            ->rawColumns(['status'])
            ->toJson();
    }

    private function diffDate($date1, $date2)
    {
        return (strtotime($date2)-strtotime($date1)) / 3600 / 24;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis = [
            '' => '-Pilih Jenis-',
            'Pesanan Wilayah' => 'Pesanan Wilayah',
            'Pesanan Lain-lain' => 'Pesanan Lain-lain'
        ];

        return view('pages.spp.create', [
            'jenis' => $jenis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDraft(Request $request)
    {
        try {
            DB::beginTransaction();

            Validator::make($request->all(), [
                'jenis'   => 'required',
                'no_npp'   => 'required',
            ])->validate();

            $noNpp = $request->no_npp;

            $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
                ->where('no_npp', $noNpp)
                ->get();

            $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();

            $sp3D = Sp3D::whereNoNpp($noNpp)
                ->whereIn('kd_produk', $kd_produks)
                ->get()
                ->sortByDesc('no_sp3')
                ->groupBy([
                    'kd_produk', function ($item) {
                        return substr($item->no_sp3, 0, -3);
                    }
                ], true);

            $sqlNpp = Npp::select('npp.nama_proyek',
                    'npp.nama_pelanggan',
                    'npp.no_npp',
                    'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                    'tb_pat.ket as pat',
                    'npp.kd_pat',
                    'tb_pat.singkatan')
                ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                ->where('npp.no_npp', $noNpp)
                ->first();

            $sp3 = DB::table('SP3_D')
                ->where('no_npp', $noNpp)
                ->where('pat_to', $sqlNpp->kd_pat)
                ->max('jarak_km');

            return view('pages.spp.box2', [
                'result' => 'success',
                'tblPesanan' => $detailPesanan,
                'npp' => $sqlNpp,
                'jarak' => $sp3,
                'noSpprb' => 'xxxxx', // NO SPPRB STILL HARDCODE
                'sp3D' => $sp3D
            ])->render();
        } catch(Exception $e) {
            return response()->json(['result' => $e->getMessage()])->setStatusCode(500, 'ERROR');
        }
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();

            if ($request->rencana) {
                $jadwal = [];
                $rencana = $request->rencana;
                $kdProduk = $request->rencana[1]['kd_produk'];
                $noSppb = $this->generateSppb($kdProduk, session('TMP_KDWIL'));

                if (!empty($request->jadwal)) {
                    $jadwal = explode(" - ", $request->jadwal);
                }
       
                $vspprbri = VSpprbRi::where('v_spprb_ri.no_npp', $request->no_npp)->first();

                $data = new SppbH;
                $data->no_sppb = $noSppb;
                $data->no_npp = $request->no_npp;
                $data->no_spprb = $vspprbri->spprblast;
                $data->tujuan = $request->tujuan;
                $data->rit = $request->rit;
                $data->jarak_km = $request->jarak_km;
                $data->catatan = $request->catatan;
                $data->jadwal1 = !empty($jadwal[0]) ? date('Y-m-d', strtotime($jadwal[0])) : date('Y-m-d', strtotime('-3 day', time()));
                $data->jadwal2 = !empty($jadwal[1]) ? date('Y-m-d', strtotime($jadwal[1])) : date('Y-m-d');
                $data->tgl_sppb = date('Y-m-d');
                $data->created_by = session('TMP_NIP');

                $data->save();

                foreach ($rencana as $row) {
                    $detail = new SppbD;

                    $detail->kd_produk = $row['kd_produk'];
                    $detail->vol = str_replace(',', '', $row['saat_ini']);
                    $detail->ket = $row['ket'];
                    $detail->segmental = !empty($row['segmental'])?$row['segmental']:'0';
                    $detail->jml_segmen = str_replace(',', '', $row['jml_segmen']);

                    $data->detail()->save($detail);
                }

                DB::commit();
                $flasher->addSuccess('Data telah berhasil disimpan!');
            } else {
                $flasher->addError('Detail Rencana Produk dikirim kosong.');
            }

            return redirect()->route('spp.index');
        } catch(Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $flasher->addError('Terjadi error silahkan coba beberapa saat lagi.');

            return redirect()->back();
        }
    }


    public function getSpprb(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            return Response::json([]);
        }

        $tags = DB::table('v_spprb_ri vsr')
            ->selectRaw("vsr.spprblast, vsr.NO_NPP,
                vsr.SPPRBLAST || ' | ' || vsr.NO_NPP || ' | ' || npp.NAMA_PROYEK as name")
            ->join('npp', 'vsr.no_npp', '=', 'npp.no_npp')
            ->where(function($query) use($term){
                $query->where('vsr.spprblast', 'like', "%$term%")
                    ->orWhere('vsr.no_npp', 'like', "%$term%")
                    ->orWhere('npp.nama_proyek', 'like', "%$term%");
            })
            ->groupBy('vsr.SPPRBLAST', 'vsr.NO_NPP', 'npp.NAMA_PROYEK')
            ->limit(5)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->spprblast.'|'.$tag->no_npp, 'text' => $tag->name];
        }

        return Response::json($formatted_tags);
    }

    private function editData($spp, $tipe)
    {
        $jenis = [
            '' => '-Pilih Jenis-',
            'Pesanan Wilayah' => 'Pesanan Wilayah',
            'Pesanan Lain-lain' => 'Pesanan Lain-lain'
        ];

        $noSppb = str_replace("|", "/", $spp);
        $data = SppbH::with('spprb')->find($noSppb);
        $npp = !empty($data->npp)?$data->npp:null;
        $noNpp = $data->no_npp;

        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $noNpp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();

        $sp3D = Sp3D::whereNoNpp($noNpp)
            ->whereIn('kd_produk', $kd_produks)
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $start = date('m/d/Y', strtotime($data->jadwal1));
        $end = date('m/d/Y', strtotime($data->jadwal2));

        return [
            'data' => $data,
            'jenis' => $jenis,
            'start' => $start,
            'end' => $end,
            'noSppb' => $noSppb,
            'spp' => $spp,
            'npp' => $npp,
            'no_npp' => $noNpp,
            'tipe' => $tipe,
            'tblPesanan' => $detailPesanan,
        ];
    }

    public function print($spp)
    {
        $noSppb = str_replace("|", "/", $spp);
        $data = SppbH::find($noSppb);

        $noNpp = $data->no_npp;

        $pesanans = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $noNpp)
            ->get();
        $dataPesanan = [];

        if (count($pesanans) > 0) {
            foreach ($pesanans as $pesanan) {
                $volm3 = !empty($pesanan->vol_m3)?$pesanan->vol_m3:1;
                $pesananVolBtg  = $pesanan->vSpprbRi->vol_spprb ?? 0;
                $pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
                $sppVolBtg = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
                $sppVolTon = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
                $sisaBtg = $pesananVolBtg - $sppVolBtg;
                $sisaTon = $pesananVolTon - $sppVolTon;
                $persen = 0;
                if ($pesananVolBtg > 0) {
                    $persen = $sisaBtg / $pesananVolBtg * 100;
                }

                $dataPesanan[$pesanan->kd_produk_konfirmasi] = [
                    'sisaBtg' => $sisaBtg,
                    'sppVolBtg' => $sppVolBtg,
                    'pesananVolBtg' => nominal($pesananVolBtg)
                ];
            }
        }
        $npp = Npp::select('npp.nama_proyek',
                    'npp.nama_pelanggan',
                    'npp.no_npp',
                    'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                    'tb_pat.ket as pat',
                    'tb_pat.kota',
                    'npp.kd_pat',
                    'spnpp.no_konfirmasi',
                    'tb_pat.singkatan')
                ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                ->leftJoin('spnpp', 'spnpp.no_npp', '=', 'npp.no_npp')
                ->where('npp.no_npp', $noNpp)
                ->first();
        $pdf = Pdf::loadView('prints.spp', [
            'data' => $data,
            'npp' => $npp,
            'dataPesanan' => $dataPesanan
        ]);

        $filename = "SPP-Report";

        return $pdf->setPaper('a4', 'portrait')
            ->stream($filename . '.pdf');
    }

    public function edit($spp)
    {
        $arrData = $this->editData($spp, 'edit');
        $arrData['lokasi_muat'] = VSpprbRi::with(['produk', 'pat'])
            ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
            ->select('v_spprb_ri.pat_to')
            ->where('v_spprb_ri.no_npp', $arrData['no_npp'])
            ->get()
            ->map(function($item){
                return $item->pat->ket;
            })
            ->unique()
            ->all();

        return view('pages.spp.edit',  $arrData);
    }

    public function show($spp)
    {
        $arrData = $this->editData($spp, 'show');

        $npp = Npp::find($arrData['no_npp']);

        $arrData['spprb'] = VSpprbRi::with(['produk', 'pat'])
            ->where('v_spprb_ri.no_npp', $arrData['no_npp'])
            ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
            ->select('v_spprb_ri.pat_to', 'v_spprb_ri.spprblast', 'v_spprb_ri.kd_produk', 'v_spprb_ri.vol_spprb', 'spprb_h.jadwal1',
                'spprb_h.jadwal2')
            ->get();

        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $arrData['angkutan'] = SppbH::join('spprb_h', 'spprb_h.no_spprb', '=', 'sppb_h.no_spprb')
                ->join('sp3_h', 'sp3_h.no_npp', '=', 'spprb_h.no_npp')
                ->join(DB::raw($joinQuery), function($join) {
                    $join->on('sp3_h.no_sp3', '=', 'last_sp3.no_sp3');
                })
                ->join('vendor', 'vendor.vendor_id', '=', 'sp3_h.vendor_id')
                ->select('spprb_h.no_spprb', 'sp3_h.no_sp3', 'sp3_h.app1', 'sp3_h.app2', 'sp3_h.st_wf', 'vendor.nama as vendorname', DB::raw("(SELECT sum(vol_akhir) FROM sp3_d WHERE NO_SP3 = sp3_h.NO_SP3) AS volakhir"), DB::raw("(SELECT sum(VOL_TON_AKHIR) FROM sp3_d WHERE NO_SP3 = sp3_h.NO_SP3) AS voltonakhir"))
                ->where('sppb_h.no_sppb', $arrData['noSppb'])
                ->get();

        if (!empty($npp)) {
            if (!empty($npp->no_info)) {
                $arrData['kontrak'] = DB::table('KD_SEPEDM_D')
                    ->where('no_proyek', $npp->no_info)
                    ->where('no_dok', '12')
                    ->whereRaw("P_KE = (select
                            max(P_KE)
                        from
                            KD_SEPEDM_D
                        WHERE
                            NO_DOK = '12'
                            AND NO_PROYEK = '$npp->no_info'
                        )")
                    ->first();
            }
        }
        
        //RUTE Data
        $pat = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        $muat = VPotensiMuat::with('pat')->where('no_npp',$arrData['no_npp'])->get();
        
        $arrData['lokasi_muat'] = VSpprbRi::with(['produk', 'pat'])
            ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
            ->select('v_spprb_ri.pat_to')
            ->where('v_spprb_ri.no_npp', $arrData['no_npp'])
            ->get()
            ->map(function($item){
                return $item->pat->ket;
            })
            ->unique()
            ->all();


        $collection_table = new Collection();
        foreach($muat as $row){
            $spprbRi = ViewsVSpprbRi::
                        select('kd_produk','pat_to','no_npp','vol_spprb')
                        ->with(['produk' => function($sql){
                            $sql->select('kd_produk','tipe', 'vol_m3');
                        }])
                        ->with(['ppb_muat' =>function($sql){
                            $sql->select('kd_pat','lat_gps','lng_gps');
                        }])
                        ->where('no_npp',$row->no_npp)
                        ->where('pat_to',$row->ppb_muat)
                        ->groupBy('kd_produk','pat_to','no_npp','vol_spprb')
                        ->get();

            $sqlNpp = Npp::select('npp.nama_proyek',
                        'npp.nama_pelanggan',
                        'npp.no_npp',
                        'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                        'tb_pat.ket as pat',
                        'npp.kd_pat',
                        'tb_pat.singkatan',
                        'info_pasar_h.lat as info_pasar_lat','info_pasar_h.lang as info_pasar_long',
                        'tb_region.lat as tb_region_lat','tb_region.lang as tb_region_long')
                    ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                    ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                    ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                    ->where('npp.no_npp', $row->no_npp)
                    ->first();

            $potensiH = PotensiH::where('no_npp',$row->no_npp)
                    ->where('pat_to', $row->ppb_muat)
                    ->first();

            $collection_table->push((object)[
                'no_npp' => $row->no_npp,
                'ppb_muat' => $row->ppb_muat,
                'vol_btg' => $row->vol_btg,
                'tonase' => $row->tonase,
                'jadwal3' => $row->jadwal3,
                'jadwal4' => $row->jadwal4,
                'jml_rit' => $row->jml_rit,
                'pat' => $row->pat->ket,
                'jarak_km' => $row->jarak_km,
                'spprbri' => $spprbRi,
                'lat_source' => $spprbRi[0]->ppb_muat->lat_gps,
                'long_source' => $spprbRi[0]->ppb_muat->lng_gps,
                'lat_dest' => $sqlNpp->info_pasar_lat ?? $sqlNpp->tb_region_lat,
                'long_dest' => $sqlNpp->info_pasar_long ?? $sqlNpp->tb_region_long,
                'destination' => $sqlNpp->kab. ',' . $sqlNpp->kec,
                'potensiH' => $potensiH
            ]);
        }

        $arrData['pat'] = $pat;
        $arrData['muat'] = $collection_table;
        // return response()->json($collection_table);
        // return view('pages.potensi-detail-armada.create', ['pat' => $pat, 'muat' => $collection_table, 'trmaterial' => $trmaterial]);
        // return response()->json($arrData);
        return view('pages.spp.show',  $arrData);
    }

    public function amandemen($spp)
    {
        $arrData = $this->editData($spp, 'amandemen');

        return view('pages.spp.edit',  $arrData);
    }

    public function update(Request $request, $spp, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();

            $noSppb = str_replace("|", "/", $spp);
            $data = SppbH::find($noSppb);

            if ($request->tipe == 'amandemen') {
                $temp = explode("/", $noSppb);
                if (strlen($temp[5]) == 4) {
                    $temp[5] .= "P01";
                } else {
                    $foo = substr($temp[5], 5) + 1;
                    $inc = sprintf("%02d", $foo);
                    $temp[5] = substr($temp[5], 0, 4).'P'.$inc;
                }

                if (count($request->rencana) > 0) {
                    $del = SppbD::where('no_sppb', $noSppb)->delete();
                }

                $noSppb = implode("/", $temp);
                $data->no_sppb = $noSppb;
            }

            $data->tujuan = $request->tujuan;
            $data->rit = $request->rit;
            $data->jarak_km = $request->jarak_km;

            if (!empty($request->jadwal)) {
                $jadwal = explode(" - ", $request->jadwal);
            }
            $data->jadwal1 = !empty($jadwal[0]) ? date('Y-m-d', strtotime($jadwal[0])) : date('Y-m-d', strtotime('-3 day', time()));
            $data->jadwal2 = !empty($jadwal[1]) ? date('Y-m-d', strtotime($jadwal[1])) : date('Y-m-d');
            $data->catatan = $request->catatan;

            $data->save();

            if (count($request->rencana) > 0) {
                $del = SppbD::where('no_sppb', $noSppb)->delete();

                foreach ($request->rencana as $row) {
                    $detail = new SppbD;

                    $detail->kd_produk = $row['kd_produk'];
                    $detail->vol = $row['saat_ini'];
                    $detail->ket = $row['ket'];
                    $detail->segmental = isset($row['segmental'])?1:0;
                    $detail->jml_segmen = $row['jml_segmen'];

                    $data->detail()->save($detail);
                }
            }

            DB::commit();
            $flasher->addSuccess('Data telah berhasil disimpan!');

            return redirect()->route('spp.index');
        } catch(Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $flasher->addError('Terjadi error silahkan coba beberapa saat lagi.');

            return redirect()->back();
        }
    }

    private function generateSppb($kdProduk, $patSingkatan)
    {
        $produk = DB::table('view_master_produk')->where('kd_produk', $kdProduk)->first();
        $singkatan = !empty($produk)?$produk->singkatan2:'RT';
        $tahun = date('Y');
        $pat = Pat::where('kd_pat', $patSingkatan)->first();

        $maks = SppbH::whereRaw("no_sppb like '%/$singkatan/%/$tahun'")->max('no_sppb');

        if (!empty($maks)) {
            $maks = substr($maks, 0, 4);

            $urutan = sprintf('%04d', $maks + 1);
        } else {
            $urutan = sprintf('%04d', 1);
        }

        $noSppb = $urutan.'/SPPB/'.$singkatan.'/'.($pat->singkatan ?? 'XX').'/'.date('m').'/'.date('Y');

        return $noSppb;
    }

    public function monitorApproval()
    {
        return view('pages.spp.monitor_approval');
    }

    public function monitorApprovalData()
    {
        $query = SppbH::with(['spprb.npp'])->select('*');
        if(!Auth::check() && session('TMP_KDWIL') != '0A'){
            $query->whereHas('npp', function($sql){
                $sql->where('kd_pat', session('TMP_KDWIL'));
            });
        }

        return DataTables::eloquent($query)
            ->editColumn('tgl_sppb', function ($model) {
                return date('d-m-Y', strtotime($model->tgl_sppb));
            })
            ->addColumn('ksdm', function ($model) {
                $teks = '';
                // if($model->app == 1){
                    $teks .= '<i class="fas fa-2x fa-check text-success"></i>';
                // }
                return $teks;
            })
            ->addColumn('peo', function ($model) {
                $teks = '';
                if($model->app2 == 1){
                    $teks .= '<i class="fas fa-2x fa-check text-success"></i>';
                }
                return $teks;
            })
            ->addColumn('munit', function ($model) {
                $teks = '';
                if($model->app3 == 1){
                    $teks .= '<i class="fas fa-2x fa-check text-success"></i>';
                }
                return $teks;
            })
            ->rawColumns(['ksdm', 'peo', 'munit'])
            ->toJson();
    }
}
