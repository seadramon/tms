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
use App\Models\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;

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
                
                switch (true) {
                    case ($model->app == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'first',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve First";
                        break;
                    case ($model->app == 1 && $model->app2 == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'second',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve Second";
                        break;
                    case ($model->app == 1 && $model->app2 == 1 && $model->app3 == 0 && $sumVolApp2 == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'third',
                            'nosppb' => $noSppb
                        ]);
                        $caption = "Approve Third";
                        break;
                    default:
                        $approve = "";
                        $caption = "";
                        break;
                }
                if ($approve!="") {
                    $approval = '<li><a class="dropdown-item" href="'.$approve.'">'. $caption .'</a></li>';
                } else {
                    $approval = "";
                }

                $edit = '<div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="'. route('spp.show', ['spp' => $noSppb]) .'">View</a></li>
                            <li><a class="dropdown-item" href="'. route('spp.edit', ['spp' => $noSppb]) .'">Edit</a></li>
                            <li><a class="dropdown-item" href="'. route('spp.amandemen', ['spp' => $noSppb]) .'">Amandemen</a></li>
                            '.$approval.'
                            <li><a class="dropdown-item" href="'. route('spp.print', ['spp' => $noSppb]) .'">Print</a></li>
                            <li><a class="dropdown-item" href="#">Hapus</a></li>
                        </ul>
                        </div>';

                return $edit;
            })
            ->rawColumns(['menu', 'waktu', 'approval'])
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
                ->take(2)
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

                $data = new SppbH;
                $data->no_sppb = $noSppb;
                $data->no_npp = $request->no_npp;
                $data->no_spprb = "140/PI/XI/WP/WP.VI/06P00"; // NO SPPRB STILL HARDCODE
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
        $data = SppbH::find($noSppb);
        $npp = !empty($data->npp)?$data->npp:null;
        $noNpp = $data->no_npp;

        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $noNpp)
            ->take(2)
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
                $pesananVolBtg  = $pesanan->vol_konfirmasi ?? 0;
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
}
