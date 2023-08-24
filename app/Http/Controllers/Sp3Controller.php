<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ban;
use App\Models\JenisPekerjaan;
use App\Models\Kontrak;
use App\Models\MonOp;
use App\Models\MsNoDokumen;
use App\Models\Npp;
use App\Models\Pat;
use App\Models\Personal;
use App\Models\Produk;
use App\Models\Sp3;
use App\Models\Sp3D;
use App\Models\Sp3D2;
use App\Models\Sp3Dokumen;
use App\Models\Sp3Pic;
use App\Models\SptbD;
use App\Models\TrMaterial;
use App\Models\Vendor;
use App\Models\Sbu;
use App\Models\Views\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class Sp3Controller extends Controller
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
            'januari'   => 'Januari',
            'februari'  => 'Februari',
            'maret'     => 'Maret',
            'april'     => 'April',
            'mei'       => 'Mei',
            'juni'      => 'Juni',
            'juli'      => 'Juli',
            'agustus'   => 'Agustus',
            'september' => 'September',
            'oktober'   => 'Oktober',
            'november'  => 'November',
            'desember'  => 'Desember'
        ];

        $jenisPekerjaan = JenisPekerjaan::get()
            ->pluck('ket', 'kd_jpekerjaan')
            ->toArray();
            
        $jenisPekerjaan = ["" => "Semua"] + $jenisPekerjaan;

        return view('pages.sp3.index', compact(
            'pat', 'periode', 'status', 'rangeCutOff', 'monthCutOff', 'muat', 'jenisPekerjaan'
        ));
    }

    public function data(Request $request)
    {
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))no_sp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $query = Sp3::with('vendor', 'sp3D', 'unitkerja')
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_h.no_sp3', '=', 'last_sp3.no_sp3');
            })
            ->select('sp3_h.no_sp3', 'sp3_h.tgl_sp3', 'sp3_h.app1', 'sp3_h.app2', 'sp3_h.no_npp', 'sp3_h.vendor_id', 'sp3_h.kd_pat', 'sp3_h.jadwal1', 'sp3_h.jadwal2');

        if($request->pat){
            $query->where('kd_pat', $request->pat);
        }
        if($request->ppb_muat){
            $query->whereHas('sp3D', function($sql) use ($request){
                $sql->where('pat_to', $request->ppb_muat);
            });
        }
        if($request->periode){
            $query->whereYear('tgl_sp3', $request->periode);
        }
        if($request->pekerjaan){
            $query->where('kd_jpekerjaan', $request->pekerjaan);
        }
        
        if(Auth::check()){
            $query->whereVendorId(Auth::user()->vendor_id)->where('app1', 1);
        }

        return DataTables::eloquent($query)
                ->editColumn('tgl_sp3', function ($model) {
                    return date('d-m-Y', strtotime($model->tgl_sp3));
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
                    $vol_sp3 = $model->sp3D->sum('vol_akhir');
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
                    $sp3d = $model->sp3D->groupBy(function($item){ return $item->kd_produk . '_' . $item->pat_to; });
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
                    $vol_sp3 = $model->sp3D->sum(function($item) { return intval($item->vol_akhir) * intval($item->harsat_akhir); });
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
                        $a = $this->diffDate($model->jadwal1, date('Y-m-d'));
                        $b = $this->diffDate($model->jadwal1, $model->jadwal2);

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
                    if(Auth::check()){
                        $list .= '<li><a class="dropdown-item" href="'.route('sp3.print', str_replace('/', '|', $model->no_sp3)).'">Print</a></li>';
                        $list .= '<li><a class="dropdown-item" href="' . url('sp3', str_replace('/', '|', $model->no_sp3)) . '">View</a></li>';
                        if($model->app1 == 1 && in_array($model->app2, [null, 0])){
                            $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['second', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                        }
                    }else{
                        $action = json_decode(session('TMS_ACTION_MENU'));
                        if(in_array('view', $action)){
                            $list .= '<li><a class="dropdown-item" href="' . url('sp3', str_replace('/', '|', $model->no_sp3)) . '">View</a></li>';
                        }
                        if(in_array('edit', $action) && $model->app1 != 1){
                            $list .= '<li><a class="dropdown-item" href="' . route('sp3.edit', str_replace('/', '|', $model->no_sp3)) . '">Edit</a></li>';
                        }
                        if(in_array('amandemen', $action) && $model->app1 == 1){
                            $list .= '<li><a class="dropdown-item" href="' . route('sp3.amandemen', str_replace('/', '|', $model->no_sp3)) . '">Amandemen</a></li>';
                        }
                        if(in_array('print', $action)){
                            $list .= '<li><a class="dropdown-item" href="'.route('sp3.print', str_replace('/', '|', $model->no_sp3)).'">Print</a></li>';
                        }
                        if(in_array('approve1', $action) && $model->app1 == 0){
                            $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['first', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                        }
                        if(in_array('approve2', $action) && $model->app1 == 1){
                            $list .= '<li><a class="dropdown-item" href="' . route('sp3.get-approve', ['second', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>';
                        }
                    }
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

        $jenisPekerjaan = JenisPekerjaan::get()
            ->pluck('ket', 'kd_jpekerjaan')
            ->toArray();
            
        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase", "ritase" => "Ritase"];
        $npp = Npp::find($request->npp);

        return view('pages.sp3.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $request->vendor_id ?? null,
        ]);
    }

    public function searchNpp(Request $request)
    {
        $query = Npp::where('no_npp', 'LIKE', '%' . $request->q . '%');

        if(session('TMP_KDWIL') != '0A'){
            $query->where('kd_pat', session('TMP_KDWIL') ?? '1A');
        }
        
        return $query->get();
    }

    public function searchPic(Request $request)
    {
        $personal = Personal::select('employee_id', 'first_name', 'last_name')
            ->where('st', 1)
            ->whereIn('kd_jbt', ['JBTP0001', 'JBTP0002'])
            ->where(function($sql) use ($request) {
                $sql->where(DB::raw('LOWER(employee_id)'), 'LIKE', '%' . $request->q . '%')
                ->orWhere(DB::raw('LOWER(first_name)'), 'LIKE', '%' . $request->q . '%')
                ->orWhere(DB::raw('LOWER(last_name)'), 'LIKE', '%' . $request->q . '%');

            });
        if(session('TMP_KDWIL') != '0A'){
            $personal->where('kd_pat', session('TMP_KDWIL') ?? '0A');
        }
        return $personal->get();
    }

    public function getDataBox2(Request $request)
    {
        $parameters = $request->all();
        
        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $parameters['no_npp'])
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
        
        $sp3D = Sp3D::whereNoNpp($parameters['no_npp'])
            ->whereIn('kd_produk', $kd_produks)
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $npp = Npp::with(['infoPasar.region'])
            ->where('no_npp', $parameters['no_npp'])
            ->first();

        $ban = Ban::where('pat_ban', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_ban', 'no_ban')->toArray();
        $ban = ["" => "---Pilih---"] + $ban;

        $kontrak = Kontrak::where('pat_kontrak', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_kontrak', 'no_kontrak')->toArray();
        $kontrak = ["" => "---Pilih---"] + $kontrak;

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

        if($VSpprbRi){
            $jarak = Sp3D::where('pat_to', $VSpprbRi->pat_to)
                ->where('no_npp', $VSpprbRi->no_npp)
                ->max('jarak_km');
        }else{
            $jarak = 0;
        }

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
        

        $html = view('pages.sp3.box2', [
            'detailPesanan' => $detailPesanan,
            'npp' => $npp,
            'ban' => $ban,
            'kontrak' => $kontrak,
            'vendor' => $vendor,
            'trader' => $trader,
            'kondisiPenyerahan' => $kondisiPenyerahan,
            'kondisiPenyerahanDipilih' => $kondisiPenyerahanDipilih,
            'VSpprbRi' => $VSpprbRi,
            'jarak' => $jarak,
            'unit' => $unit,
            'satuan' => $satuan,
            'ppn' => $ppn,
            'pph' => $pph,
            'sp3D' => $sp3D,
            'sat_harsat' => $request->sat_harsat,
            'kd_material' => $kd_material,
        ])->render();
        
        return response()->json( array('success' => true, 'html'=> $html) );
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'no_npp'        => 'required',
                'vendor_id'     => 'required',
                'kd_jpekerjaan' => 'required',
            ])->validate();

            $vendor = Vendor::find($request->vendor_id);
            
            $noDokumen = 'TP.02.01/WB-' . (session('TMP_KDWIL') ?? '1A');

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

            $noSp3 = $noDokumen . '.' . $newSequence . '/' . date('Y') . 'P00';
            $pph = explode('|', $request->pph);

            $sp3 = new Sp3();
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->kd_jpekerjaan = $request->kd_jpekerjaan;
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
                $sp3D->jarak_km = str_replace(',', '', $request->jarak_pekerjaan[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if(strtolower($request->sat_harsat) == 'tonase'){
                    $sp3D->sat_harsat = $request->satuan[$i];
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

            for($i=0; $i < count($request->dokumen_asli); $i++){
                $sp3Dokumen = new Sp3Dokumen();

                $sp3Dokumen->no_sp3 = $noSp3;
                $sp3Dokumen->dok_id = $i + 1;
                $sp3Dokumen->asli = $request->dokumen_asli[$i];
                $sp3Dokumen->copy = $request->dokumen_copy[$i];
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

    public function show($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);
        
        $data = Sp3::with('sp3D')->find($noSp3);

        $detailPesanan = MonOp::with(['produk', 'sp3D' => function($sql) use ($noSp3) {
                $sql->whereNotIn('no_sp3', [$noSp3]);
            }, 'vSpprbRi'])
            ->where('no_npp', $data->no_npp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
        
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))nosp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $sp3D = Sp3D::whereNoNpp($data->no_npp)
            ->whereIn('kd_produk', $kd_produks)
            ->whereNotIn('no_sp3', [$noSp3])
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_d.no_sp3', '=', 'last_sp3.nosp3');
            })
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $npp = Npp::with(['infoPasar.region'])
            ->where('no_npp', $data->no_npp)
            ->first();

        $ban = Ban::where('pat_ban', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_ban', 'no_ban');

        $kontrak = Kontrak::where('pat_kontrak', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_kontrak', 'no_kontrak');

        $vendor = Vendor::where('vendor_id', $data->vendor_id)->first();
        $trader = DB::connection('oracle-eproc')
					->table(DB::raw('"m_trader"'))
					->where('vendor_id', $data->vendor_id)
					->first();
        $kondisiPenyerahan = [
            'L' => 'LOKO', 
            'F' => 'FRANKO', 
            'T' => 'TERPASANG', 
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($data->no_npp, -1))];

        $VSpprbRi = VSpprbRi::where('no_npp', $data->no_npp)->first();

        if($VSpprbRi){
            $jarak = Sp3D::where('pat_to', $VSpprbRi->pat_to)
                ->where('no_npp', $VSpprbRi->no_npp)
                ->max('jarak_km');
        }else{
            $jarak = 0;
        }

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
            "10" => "10%",
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

        $sat_harsat = $data->satuan_harsat;

        $listPic = Sp3Pic::with('employee')
            ->where('no_sp3', $noSp3)
            ->get();

        $detailPekerjaan = Sp3D::where('no_sp3', $noSp3)->get();

        $materialTambahan = Sp3D2::where('no_sp3', $noSp3)->get();

        $isAmandemen = str_contains(request()->url(), 'amandemen');

        $sptbd_ = SptbD::with('sptbh')->whereHas('sptbh', function($sql) use ($data){
                $sql->whereNoNpp($data->no_npp);
                $sql->whereHas('spmh', function($sql1) use ($data){
                    $sql1->whereVendorId($data->vendor_id);
                });
            })
            ->get();
        $sptbd = $sptbd_->groupBy('kd_produk');

        return view('pages.sp3.show', compact(
            'data',
            'detailPesanan',
            'npp',
            'ban',
            'kontrak',
            'vendor',
            'trader',
            'kondisiPenyerahan',
            'kondisiPenyerahanDipilih',
            'VSpprbRi',
            'jarak',
            'unit',
            'satuan',
            'ppn',
            'pph',
            'sp3D',
            'sptbd',
            'sptbd_',
            'sat_harsat',
            'listPic',
            'detailPekerjaan',
            'materialTambahan',
            'isAmandemen'
        ));
    }

    public function edit($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);

        $detailPesanan = MonOp::with(['produk', 'sp3D' => function($sql) use ($noSp3) {
                $sql->whereNotIn('no_sp3', [$noSp3]);
            }, 'vSpprbRi'])
            ->where('no_npp', $data->no_npp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
        
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))nosp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $sp3D = Sp3D::whereNoNpp($data->no_npp)
            ->whereIn('kd_produk', $kd_produks)
            ->whereNotIn('no_sp3', [$noSp3])
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_d.no_sp3', '=', 'last_sp3.nosp3');
            })
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $npp = Npp::with(['infoPasar.region'])
            ->where('no_npp', $data->no_npp)
            ->first();

        $ban = Ban::where('pat_ban', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_ban', 'no_ban');

        $kontrak = Kontrak::where('pat_kontrak', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_kontrak', 'no_kontrak');

        $vendor = Vendor::where('vendor_id', $data->vendor_id)->first();
        $trader = DB::connection('oracle-eproc')
					->table(DB::raw('"m_trader"'))
					->where('vendor_id', $data->vendor_id)
					->first();

        $kondisiPenyerahan = [
            'L' => 'LOKO', 
            'F' => 'FRANKO', 
            'T' => 'TERPASANG', 
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($data->no_npp, -1))];

        $VSpprbRi = VSpprbRi::where('no_npp', $data->no_npp)->first();

        if($VSpprbRi){
            $jarak = Sp3D::where('pat_to', $VSpprbRi->pat_to)
                ->where('no_npp', $VSpprbRi->no_npp)
                ->max('jarak_km');
        }else{
            $jarak = 0;
        }

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

        $sat_harsat = $data->satuan_harsat;

        $listPic = Sp3Pic::with('employee')
            ->where('no_sp3', $noSp3)
            ->get();

        $detailPekerjaan = Sp3D::where('no_sp3', $noSp3)->get();

        $materialTambahan = Sp3D2::where('no_sp3', $noSp3)->get();

        $isAmandemen = str_contains(request()->url(), 'amandemen');

        $kd_material = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->pluck('name', 'kd_material')
            ->toArray();

        $sptbd_ = SptbD::with('sptbh')->whereHas('sptbh', function($sql) use ($data){
            $sql->whereNoNpp($data->no_npp);
            $sql->whereHas('spmh', function($sql1) use ($data){
                $sql1->whereVendorId($data->vendor_id);
            });
        })
        ->get();
        $sptbd = $sptbd_->groupBy('kd_produk');

        return view('pages.sp3.edit', compact(
            'data',
            'detailPesanan',
            'npp',
            'ban',
            'kontrak',
            'vendor',
            'trader',
            'kondisiPenyerahan',
            'kondisiPenyerahanDipilih',
            'VSpprbRi',
            'jarak',
            'unit',
            'satuan',
            'ppn',
            'pph',
            'sp3D',
            'sptbd',
            'sptbd_',
            'sat_harsat',
            'listPic',
            'detailPekerjaan',
            'materialTambahan',
            'isAmandemen',
            'kd_material'
        ));
    }

    public function update(Request $request, FlasherInterface $flasher, $noSp3){
        try {
            DB::beginTransaction();

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
            $sp3->no_sp3 = $newNoSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->kd_jpekerjaan = $request->kd_jpekerjaan;
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
            $sp3->save();

            foreach($request->pic as $pic){
                $sp3Pic = new Sp3Pic();
                $sp3Pic->no_sp3 = $newNoSp3;
                $sp3Pic->employee_id = $pic;
                $sp3Pic->save();
            }

            for($i=0; $i < count($request->unit); $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $newNoSp3;
                $sp3D->no_npp = $sp3->no_npp;
                $sp3D->pat_to = $request->unit[$i];
                $sp3D->kd_produk = $request->tipe[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak_pekerjaan[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if(strtolower($request->sat_harsat) == 'tonase'){
                    $sp3D->sat_harsat = $request->satuan[$i];
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
                $sp3D2->no_sp3 = $newNoSp3;
                $sp3D2->material = $material['material'];
                $sp3D2->spesifikasi = $material['spesifikasi'];
                $sp3D2->volume = $material['volume'];
                $sp3D2->save();
            }

            for($i=0; $i < count($request->dokumen_asli); $i++){
                $sp3Dokumen = new Sp3Dokumen();

                $sp3Dokumen->no_sp3 = $newNoSp3;
                $sp3Dokumen->dok_id = $i + 1;
                $sp3Dokumen->asli = $request->dokumen_asli[$i];
                $sp3Dokumen->copy = $request->dokumen_copy[$i];
                $sp3Dokumen->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        // return $request->isAmandemen 
        //     ? redirect()->route('sp3.amandemen', str_replace('/', '|', $newNoSp3))
        //     : redirect()->route('sp3.edit', str_replace('/', '|', $newNoSp3));
        return redirect()->route('sp3.index');
    }

    public function showApprove($type, $noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);
        $data = Sp3::find($noSp3);

        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $data->no_npp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
    
        $joinQuery = '(SELECT substr(no_sp3, 1, LENGTH(no_sp3)-2)|| max(substr(no_sp3,-2))nosp3 FROM sp3_h GROUP BY substr(no_sp3, 1, LENGTH(no_sp3)-2))last_sp3';
        $sp3D = Sp3D::whereNoNpp($data->no_npp)
            ->whereIn('kd_produk', $kd_produks)
            ->whereNotIn('no_sp3', [$noSp3])
            ->join(DB::raw($joinQuery), function($join) {
                $join->on('sp3_d.no_sp3', '=', 'last_sp3.nosp3');
            })
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $kondisiPenyerahan = [
            'L' => 'LOKO', 
            'F' => 'FRANKO', 
            'T' => 'TERPASANG', 
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($data->no_npp, -1))];

        $listPic = [];

        foreach($data->pic as $pic){
            $listPic[] = $pic->employee->employee_id . ' - ' . $pic->employee->first_name . ($pic->employee->last_name ? ' - ' . $pic->employee->last_name : '');
        }

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

        return view('pages.sp3.approve', compact(
            'data', 'detailPesanan', 'sp3D', 'kondisiPenyerahanDipilih', 'type', 'listPic', 'pph', 'ppn'
        ));
    }

    public function storeApprove(Request $request)
    {
        $data = Sp3::find($request->no_sp3);

        if($request->type == 'first'){
            $data->app1 = 1;
            $data->app1_empid = session('TMP_NIP') ?? '12345';
            $data->app1_jbt = session('TMP_KDJBT') ?? '12345';
            $data->app1_date = date('Y-m-d H:i:s');
        }else{
            $data->app2 = 1;
            $data->app2_jbt = Auth::user()->position ?? '';
            $data->app2_date = date('Y-m-d H:i:s');
            $data->app2_name = Auth::user()->name ?? '';
        }

        $data->save();

        return redirect()->route('sp3.index');
    }

    public function print($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);
        $detail = $data->sp3D;
        $sp3pics = $data->pic;
        
        $sbu = null;
        if ($detail->count() > 0) {
            $sbu = Sbu::where('kd_sbu',  substr($detail[0]->kd_produk, 1, 1))->first();
        }

        $VSpprbRi = VSpprbRi::where('no_npp', $data->no_npp)->first();

        $pics = "";
        if (count($sp3pics) > 0) {
            $tmp = [];
            foreach ($sp3pics as $sp3pic) {
                $tmp[] = $sp3pic->employee->first_name.' '.$sp3pic->employee->last_name;
            }
            $pics = implode(", ", $tmp);
        }

        $pdf = Pdf::loadView('prints.sp3', [
            'data' => $data,
            'detail' => $detail,
            'sbu' => $sbu,
            'pics' => $pics,
            'vspprbRi' => $VSpprbRi
            // 'npp' => $npp,
            // 'dataPesanan' => $dataPesanan
        ]);

        $filename = "SP3-Report";

        return $pdf->setPaper('a4', 'portrait')
            ->stream($filename . '.pdf');
    }

    private function diffDate($date1, $date2)
    {
        return (strtotime($date2)-strtotime($date1)) / 3600 / 24;
    }
}