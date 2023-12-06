<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ban;
use App\Models\JenisPekerjaan;
use App\Models\Kontrak;
use App\Models\MonOp;
use App\Models\MsNoDokumen;
use App\Models\Npp;
use App\Models\Pat;
use App\Models\Pelabuhan;
use App\Models\Personal;
use App\Models\Produk;
use App\Models\Region;
use App\Models\Sp3;
use App\Models\Sp3D;
use App\Models\Sp3D2;
use App\Models\Sp3Dokumen;
use App\Models\Sp3Pic;
use App\Models\SptbD;
use App\Models\TrMaterial;
use App\Models\Vendor;
use App\Models\Sbu;
use App\Models\Spk;
use App\Models\SpkD;
use App\Models\SpkPasal;
use App\Models\Views\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkController extends Controller
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
        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $npp = Npp::find($request->npp);

        return view('pages.spk.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $request->vendor_id ?? null,
            'data' => null,
            'mode' => "create",
            'jenis_angkutan' => null
        ]);
    }

    public function getDataBox2(Request $request)
    {
        $code = 200;
        try {
            $parameters = $request->all();

            $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
                ->where('no_npp', $parameters['no_npp'])
                ->get();

            $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();

            // $sp3D = Sp3D::whereNoNpp($parameters['no_npp'])
            $spk_d = SpkD::whereHas('spk_h', function($sql) use ($parameters) {
                    $sql->whereNoNpp($parameters['no_npp']);
                })
                ->whereIn('kd_produk', $kd_produks)
                ->get()
                ->sortByDesc('no_spk')
                ->groupBy([
                    'kd_produk', 'no_spk'
                ], true);

            $npp = Npp::with(['infoPasar.region'])
                ->where('no_npp', $parameters['no_npp'])
                ->first();

            $ban = Ban::get()->pluck('no_ban', 'no_ban')->toArray();
            $ban = ["" => "---Pilih---"] + $ban;
            $opt_ban = Ban::get()->mapWithKeys(function($item){ 
                return [$item->no_ban => ['data-tgl' => date('d-m-Y', strtotime($item->tgl_ban))]];
            })
            ->all();

            // $kontrak = Kontrak::get()->pluck('no_kontrak', 'no_kontrak')->toArray();
            // $kontrak = ["" => "---Pilih---"] + $kontrak;

            $vendor = Vendor::where('vendor_id', $parameters['vendor_id'])->first();
            $trader = DB::connection('oracle-eproc')
                        ->table(DB::raw('"m_trader"'))
                        ->where('vendor_id', $parameters['vendor_id'])
                        ->first();

            $kondisiPenyerahan = [
                'L' => 'LOKO',
                'F' => 'FRANKO',
                'T' => 'TERPASANG',
                'D' => 'DISPENSASI'
            ];

            $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($parameters['no_npp'], -1))];

            $VSpprbRi = VSpprbRi::where('no_npp', $parameters['no_npp'])->first();

            // if($VSpprbRi){
            //     $jarak = Sp3D::where('pat_to', $VSpprbRi->pat_to)
            //         ->where('no_npp', $VSpprbRi->no_npp)
            //         ->max('jarak_km');
            // }else{
            //     $jarak = 0;
            // }

            $unit = Pat::where('kd_pat', 'LIKE', '2%')
                ->orWhere('kd_pat', 'LIKE', '4%')
                ->orWhere('kd_pat', 'LIKE', '5%')
                ->get()
                ->pluck('ket', 'kd_pat')
                ->toArray();

            $unit = ["" => "Pilih Unit"] + $unit;

            $satuan = [
                "" => "Pilih",
                "btg" => "BTG",
                "ton" => "TON",
            ];

            $ppn = [
                "0" => "0%",
                "11" => "11%",
            ];
            $pph = DB::table('tb_pph_d')->leftJoin('tb_pph_h', 'tb_pph_d.pph_id', '=', 'tb_pph_h.pph_id')
                ->select('tb_pph_d.pph_id', 'tb_pph_d.ket', 'tb_pph_h.pph_nama','tb_pph_d.value')
                ->get()
                ->sortBy(['pph_id', 'value'])
                ->mapWithKeys(function($item){
                    return [$item->pph_id . '|' . $item->value => $item->pph_nama . ' [' . $item->value . '%]'];
                })
                ->all();

            $pph = ["0|0" => "0%"] + $pph;

            $kd_material = TrMaterial::where('kd_jmaterial', 'T')
                ->get()
                ->pluck('name', 'kd_material')->toArray();
            $kd_material = ["" => "---Pilih---"] + $kd_material;

            $spesifikasi = [
                "DTD" => "Angkutan Laut Door to Door",
                "DTP" => "Angkutan Laut Door to Port",
                "PTP" => "Angkutan Laut Port to Port"
            ];

            $pelabuhan = Pelabuhan::all()
                ->mapWithKeys(function($item){
                    return [$item->nama => $item->nama];
                })
                ->all();
            $pelabuhan = ["" => "---Pilih---"] + $pelabuhan;

            $site = Region::select('kecamatan_name')
                ->groupBy('kecamatan_name')
                ->get()
                ->mapWithKeys(function($item){
                    return [$item->kecamatan_name => $item->kecamatan_name];
                })
                ->all();
            $site = ["" => "---Pilih---"] + $site;

            if(in_array($request->mode, ['edit', 'show'])){
                $spk = Spk::find($request->spk);
            }else{
                $spk = null;
            }

            $documents = [
                '1' => 'Faktur / Invoice / Kwitansi',
                '2' => 'Packing List',
                '3' => 'Faktur Pajak',
                '4' => 'BAPB',
                '5' => 'SP3 / SPK',
                '6' => 'BA Pemeriksaan / Opname',
                '7' => 'Surat Jalan / SPtB',
                '8' => 'BA Pembayaran',
                '9' => 'Rekap Surat Jalan / SPtB',
                '10' => 'Lembar Kendali Pembayaran'
            ];

            $html = view('pages.spk.box2', [
                'detailPesanan' => $detailPesanan,
                'npp' => $npp,
                'ban' => $ban,
                'opt_ban' => $opt_ban,
                'vendor' => $vendor,
                'trader' => $trader,
                'kondisiPenyerahan' => $kondisiPenyerahan,
                'kondisiPenyerahanDipilih' => $kondisiPenyerahanDipilih,
                'VSpprbRi' => $VSpprbRi,
                // 'jarak' => $jarak,
                'unit' => $unit,
                'satuan' => $satuan,
                'ppn' => $ppn,
                'pph' => $pph,
                'spk_d' => $spk_d,
                'sat_harsat' => $request->sat_harsat,
                'kd_material' => $kd_material,
                'spesifikasi' => $spesifikasi,
                'documents' => $documents,
                'pelabuhan' => $pelabuhan,
                'site' => $site,
                'spk' => $spk,
                'mode' => $request->mode,
                'pekerjaan' => $request->kdjpekerjaan,
            ])->render();
            $result = array('success' => true, 'html'=> $html);

        } catch(Exception $e) {
            $code = 400;
            $result = array('success' => false, 'message'=> $e->getMessage());
        }

        return response()->json($result, $code);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        // return response()->json($request->all());
        // try {
            DB::beginTransaction();

            Validator::make($request->all(), [
                'no_npp'        => 'required',
                'vendor_id'     => 'required',
                'kd_jpekerjaan' => 'required',
            ])->validate();

            $vendor = Vendor::find($request->vendor_id);

            $noDokumen = 'KU.08.02/WB-' . (session('TMP_KDWIL') ?? '1A');

            $msNoDokumen = MsNoDokumen::where('tahun', date('Y'))->where('no_dokumen', $noDokumen);

            if($msNoDokumen->exists()){
                $msNoDokumen = $msNoDokumen->first();

                $newSequence = sprintf('%04s', ((int)$msNoDokumen->seq + 1));

                $msNoDokumen->update([
                    'seq'           =>  $newSequence,
                    'updated_by'    => session('TMP_NIP') ?? '12345',
                    'updated_date'  => date('Y-m-d H:i:s'),
                ]);
            }else{
                $newSequence = '0001';

                $msNoDokumenData = new MsNoDokumen();
                $msNoDokumenData->tahun = date('Y');
                $msNoDokumenData->no_dokumen = $noDokumen;
                $msNoDokumenData->seq = $newSequence;
                $msNoDokumenData->created_by = session('TMP_NIP') ?? '12345';
                $msNoDokumenData->created_date = date('Y-m-d H:i:s');
                $msNoDokumenData->save();
            }

            $noSpk = $noDokumen . '.' . $newSequence . '/' . date('Y') . '';
            $pph = explode('|', $request->pph);
            $data_ = [];

            $spk = new Spk;
            $spk->no_spk = $noSpk;
            $spk->no_npp = $request->no_npp;
            $spk->vendor_id = $vendor->vendor_id;
            $spk->satuan_harsat = $request->sat_harsat;
            $spk->tgl_spk = date('Y-m-d', strtotime($request->tgl_spk));
            $spk->no_ban = $request->no_ban;
            $spk->tgl_ban = date('Y-m-d', strtotime($request->tgl_ban));
            $spk->jadwal1 = date('Y-m-d', strtotime($request->jadwal1));
            $spk->jadwal2 = date('Y-m-d', strtotime($request->jadwal2));
            $spk->ppn = $request->ppn;
            $spk->pph = $pph[1];
            $spk->pph_id = $pph[0];
            $spk->pihak1 = $request->pihak1;
            $spk->pihak1_jabatan = $request->pihak1_jabatan;
            $spk->pihak1_ket = $request->pihak1_ket;
            $spk->pihak2 = $request->pihak2;
            $spk->pihak2_jabatan = $request->pihak2_jabatan;
            $spk->pihak2_ket = $request->pihak2_ket;
            $spk->created_by = session('TMP_NIP') ?? '12345';
            $spk->kd_pat = session('TMP_KDWIL') ?? '1A';
            if($request->kd_jpekerjaan == 'laut'){
                $spk->kd_jpekerjaan = '20';
                $spk->spesifikasi = $request->spesifikasi;
                
                $harga_include = collect(json_decode($request->harga_include))->map(function($item){ return $item->value; })->all();
            }else{
                $spk->kd_jpekerjaan = '01';
            }
            // if(strtolower($request->sat_harsat) == 'ritase'){
                //     $data['harga_satuan_ritase'] = $request->harga_satuan_ritase;
                // }
            $data_['harga_include'] = $harga_include;
            $data_['proyek'] = $request->proyek;
            $data_['pelanggan'] = $request->pelanggan;
            $data_['region'] = $request->region;
            $spk->data = $data_;
            $spk->save();

            // foreach($request->pic as $pic){
            //     $spkPic = new Sp3Pic();
            //     $sp3Pic->no_sp3 = $noSp3;
            //     $sp3Pic->employee_id = $pic;
            //     $sp3Pic->save();
            // }

            foreach ($request->unit as $index => $item) {
                $spk_d = new SpkD;
                $spk_d->no_spk = $noSpk;
                $spk_d->kd_produk = $request->tipe[$index];
                $spk_d->pat_to = $item;
                $spk_d->jarak = str_replace(',', '', $request->jarak[$index]);
                $spk_d->vol_btg = str_replace(',', '', $request->vol_btg[$index]);
                $spk_d->vol_ton = str_replace(',', '', $request->vol_ton[$index]);
                $spk_d->harsat = str_replace(',', '', $request->harsat[$index]);
                $spk_d->total = str_replace(',', '', $request->jumlah[$index]);
                if(strtolower($request->sat_harsat) == 'tonase'){
                    $spk_d->satuan = $request->satuan[$index];
                }else{
                    $spk_d->ritase = str_replace(',', '', $request->ritase[$index]);
                }
                if($request->kd_jpekerjaan == 'laut'){
                    $spk_d->port_asal = $request->pelabuhan_asal[$index] ?? null;
                    $spk_d->port_tujuan = $request->pelabuhan_tujuan[$index] ?? null;
                }
                $spk_d->save();
            }

            foreach ($request->pasal as $index => $pasal) {
                $spk_pasal = new SpkPasal;
                $spk_pasal->no_spk = $noSpk;
                $spk_pasal->pasal = ($index + 1);
                $spk_pasal->judul = ($index + 1);
                $spk_pasal->keterangan = $pasal['pasal_isi'];
                $spk_pasal->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        // } catch(Exception $e) {
        //     DB::rollback();

        //     $flasher->addError($e->getMessage());

        //     return redirect()->back()->withInput()->withErrors($e->getMessage());
        // }

        return redirect()->route('spk.index');
    }

    public function edit($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);

        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $jenis_angkutan = $data->kd_jpekerjaan == '20' ? 'laut' : 'darat';
        $npp = Npp::find($data->no_npp);

        return view('pages.spk.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $data->vendor_id ?? null,
            'mode' => "edit",
            'data' => $data,
            'jenis_angkutan' => $jenis_angkutan
        ]);
    }

    public function update(Request $request, FlasherInterface $flasher, $noSp3){
        // return response()->json($request->all());
        DB::beginTransaction();
        try {

            $noSp3 = str_replace('|', '/', $noSp3);

            if($request->isAmandemen){
                $noSp3Sequence = sprintf('%02s', ((int)substr($noSp3, -2))+1);

                $newNoSp3 = str_replace(substr($noSp3, -2), $noSp3Sequence, $noSp3);

                $sp3 = new Sp3();
            }else{
                $newNoSp3 = $noSp3;

                $sp3 = Sp3::find($noSp3);

                //Delete child data
                Sp3Pic::where('no_sp3', $noSp3)->delete();
                Sp3D::where('no_sp3', $noSp3)->delete();
                Sp3D2::where('no_sp3', $noSp3)->delete();
                Sp3Dokumen::where('no_sp3', $noSp3)->delete();
            }

            $vendor = Vendor::find($request->vendor_id);

            $pph = explode('|', $request->pph);

            //Update or Create Sp3
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->tgl_sp3 = date('Y-m-d', strtotime($request->tgl_sp3));
            $sp3->no_ban = $request->no_ban;
            $sp3->no_kontrak_induk = $request->no_kontrak_induk;
            $sp3->jadwal1 = date('Y-m-d', strtotime($request->jadwal1));
            $sp3->jadwal2 = date('Y-m-d', strtotime($request->jadwal2));
            $sp3->rit = $request->rit;
            $sp3->jarak_km = $request->jarak_pesanan;
            $sp3->ppn = $request->ppn ? (float)($request->ppn / 100) : 0;
            $sp3->pph = $pph[1];
            $sp3->pph_id = $pph[0];
            $sp3->keterangan = $request->keterangan;
            $sp3->kd_material = $request->kd_material;
            $sp3->created_by = session('TMP_NIP') ?? '12345';
            $sp3->created_date = date('Y-m-d H:i:s');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
            if($request->kd_jpekerjaan == 'laut'){
                $sp3->kd_jpekerjaan = '20';
                $sp3->spesifikasi = $request->spesifikasi;
                
                $harga_include = collect(json_decode($request->harga_include))->map(function($item){ return $item->value; })->all();
                $data_ = $sp3->data;
                $data_['harga_include'] = $harga_include;
                
                $sp3->data = $data_;
            }else{
                $sp3->kd_jpekerjaan = '01';
            }
            $sp3->save();

            foreach($request->pic as $pic){
                $sp3Pic = new Sp3Pic();
                $sp3Pic->no_sp3 = $noSp3;
                $sp3Pic->employee_id = $pic;
                $sp3Pic->save();
            }

            for($i=0; $i < count($request->unit); $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $noSp3;
                $sp3D->no_npp = $request->no_npp;
                $sp3D->pat_to = $request->unit[$i];
                $sp3D->kd_produk = $request->tipe[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if(strtolower($request->sat_harsat) == 'tonase'){
                    $sp3D->sat_harsat = $request->satuan[$i];
                }else{
                    $sp3D->ritase = $request->ritase[$i] ?? null;
                }
                if($request->kd_jpekerjaan == 'laut'){
                    $sp3D->port_asal = $request->pelabuhan_asal[$i] ?? null;
                    $sp3D->port_tujuan = $request->pelabuhan_tujuan[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->total = str_replace(',', '', $request->jumlah[$i]);
                }

                $sp3D->harsat_awal = str_replace(',', '', $request->harsat[$i]);
                $sp3D->harsat_akhir = str_replace(',', '', $request->harsat[$i]);
                $sp3D->save();
            }

            $sp3D2Id = Sp3D2::max('id') ?? 0;

            foreach(($request->material_tambahan ?? []) as $material){
                $sp3D2Id++;

                $sp3D2 = new Sp3D2();
                $sp3D2->id = $sp3D2Id;
                $sp3D2->no_sp3 = $noSp3;
                $sp3D2->material = $material['material'];
                $sp3D2->spesifikasi = $material['spesifikasi'];
                $sp3D2->volume = $material['volume'];
                $sp3D2->save();
            }

            foreach ($request->dokumen_asli as $key => $item) {
                $sp3Dokumen = new Sp3Dokumen();
    
                $sp3Dokumen->no_sp3 = $noSp3;
                $sp3Dokumen->dok_id = $key;
                $sp3Dokumen->asli = $item;
                $sp3Dokumen->copy = $request->dokumen_copy[$key];
                $sp3Dokumen->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sp3.index');
    }   

    public function show($noSpk)
    {
        $noSpk = str_replace('|', '/', $noSpk);

        $data = Spk::find($noSpk);

        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $jenis_angkutan = $data->kd_jpekerjaan == '20' ? 'laut' : 'darat';
        $npp = Npp::find($data->no_npp);

        return view('pages.spk.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $data->vendor_id ?? null,
            'mode' => "show",
            'data' => $data,
            'jenis_angkutan' => $jenis_angkutan
        ]);
    }
}
