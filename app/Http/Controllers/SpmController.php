<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Armada;
use App\Models\ArmadaCriteria;
use App\Models\ArmadaRating;
use App\Models\ArmadaRatingDetail;
use App\Models\Sp3D;
use App\Models\SpmH;
use App\Models\SpmD;
use App\Models\SptbD;
use App\Models\MsNoDokumen;
use App\Models\Vendor;
use App\Models\Npp;
use App\Models\Sbu;
use App\Models\SptbH;
use App\Models\Views\VSpprbRi;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SpmController extends Controller
{
    public function index()
    {
        $vendor = [];
        $criterias = ArmadaCriteria::all();
        return view('pages.spm.index', [
            'vendor' => $vendor,
            'criterias' => $criterias,
        ]);
    }

    public function selectPat(Request $request)
    {
        $pat = $request->pat;
        $result = [];

        if (!empty($pat)) {
            $jalurs = DB::table('oee_ref_jalur_h')
                ->where('kode_pat', $pat)
                ->get();

            if (count($jalurs) > 0) {
                foreach ($jalurs as $row) {
                    $result[] = [
                        'id' => $row->jalur,
                        'text' => $row->jalur.'. '.$row->fungsi_jalur
                    ];
                }
            }
        }

        return response()->json($result);
    }

    public function data(Request $request)
    {
        $query = SpmH::with(['sppb', 'vendornya', 'sptbh']);
        if(Auth::check()){
            $query->whereVendorId(Auth::user()->vendor_id);
        }
        if(!Auth::check() && session('TMP_KDWIL') != '0A'){
			$query->whereHas('sppb.npp', function($sql){
                $sql->where('kd_pat', session('TMP_KDWIL'));
            })
            ->orWhere('pat_to', session('TMP_KDWIL'));
		}
        return DataTables::eloquent($query)
            ->editColumn('tgl_spm', function ($model) {
                return date('d-m-Y', strtotime($model->tgl_spm));
            })
            ->addColumn('status', function($model) {
                $teks = '';
                if(!in_array($model->jalur, ['', null])){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">Jalur&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                if($model->no_pol != null){
                    $teks .= '<span class="badge badge-light-success mr-2 mb-2">Nopol&nbsp;<i class="fas fa-check text-success"></i></span>';
                }
                return $teks;
            })
            ->addColumn('menu', function ($model) {
                $list = '';
                    if(Auth::check()){
                        $list .= '<li><a class="dropdown-item" href="' . route('spm.create-konfirmasi-vendor', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Konfirmasi Vendor</a></li>';
                        $list .= '<li><a class="dropdown-item" href="' . route('spm.show', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">View</a></li>';
                    }else{
                        $action = json_decode(session('TMS_ACTION_MENU'));
                        if($model->app1 != 1 && in_array('konfirmasi', $action)){
                            // $list .= '<li><a class="dropdown-item konfirmasi" href="#" data-bs-toggle="modal" data-bs-target="#modal_konfirmasi" data-pat="'. $model->pat_to .'" data-id="'. $model->no_spm .'">Konfirmasi</a></li>';
                            $list .= '<li><a class="dropdown-item" href="' . route('spm.konfirmasi-link', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Konfirmasi</a></li>';
                        }
                        if(in_array('konfirmasi_vendor', $action)){
                            $list .= '<li><a class="dropdown-item" href="' . route('spm.create-konfirmasi-vendor', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Konfirmasi Vendor</a></li>';
                        }
                        if(in_array('print', $action)){
                            $list .= '<li><a class="dropdown-item" href="' . route('spm.print', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Print</a></li>';
                        }
                        if(in_array('buat_sptb', $action) && $model->sptbh == null){
                            $list .= '<li><a class="dropdown-item" href="' . route('sptb.create', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Buat SPTB</a></li>';
                        }
                        if(in_array('edit', $action)){
                            $list .= '<li><a class="dropdown-item" href="' . route('spm.edit', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">Edit</a></li>';
                        }
                        if(in_array('view', $action)){
                            $list .= '<li><a class="dropdown-item" href="' . route('spm.show', ['spm' => str_replace('/', '|', $model->no_spm)]) . '">View</a></li>';
                        }
                        if(in_array('armada_tiba', $action)){
                            $list .= '<li><a class="dropdown-item armada-tiba" href="javascript:void(0)" data-spm="' . $model->no_spm . '">Armada Tiba</a></li>';
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
            ->rawColumns(['menu', 'status'])
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        if(session('TMP_KDWIL') != '0A'){
            $no_spp = SppbH::whereHas('spprb', function($sql){
                    $sql->where('kd_pat', session('TMP_KDWIL'));
                })
                ->where(function($sql){
                    $sql->where('app2',1)->orWhere('app3',1);
                })
                ->get();
        }else{
            $no_spp = SppbH::where('app2',1)->orWhere('app3',1)->get();
        }
        return view('pages.spm.create', [
            'no_spp' => $no_spp
        ]);
    }

    public function getPbbMuat(Request $request){

        $data = SppbH::select('no_npp','jadwal1','jadwal2')->where('no_sppb',$request->no_spp)->first();
        $data_1 = SpprbH::with('pat')->where('no_npp',$data->no_npp)->whereNotNull('pat_to')->get();
        $lokasi_muat = VSpprbRi::with(['produk', 'ppb_muat'])
            ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
            ->select('v_spprb_ri.pat_to')
            ->where('v_spprb_ri.no_npp', $data->no_npp)
            ->get()
            ->mapWithKeys(function($item){
                return [$item->pat_to => $item->ppb_muat->ket];
            })
            ->unique()
            ->all();
        return response()->json([
            'data_1' => $data_1,
            'min' => date("Y-m-d", strtotime($data->jadwal1)),
            'max' => date("Y-m-d", strtotime($data->jadwal2)),
            'lokasi_muat' => $lokasi_muat,
        ]);

    }

    public function getDataBox2(Request $request){

        $no_spp = $request->no_spp;
        
        $detail_spp = SppbD::with('produk')->where('no_sppb',$request->no_spp)->get();

        $collection_table = new Collection();
        foreach($detail_spp as $item){

            $data_segmen = SppbD::select('jml_segmen','app2_vol')
                ->where('no_sppb',$no_spp)
                ->where('kd_produk',$item->produk->kd_produk)
                ->first();

            $spm = SpmH::with(['spmd' => function($sql) use ($item){
                    $sql->where('kd_produk',$item->produk->kd_produk);
                }])
                ->where('no_sppb',$no_spp)
                ->first();
                $jml = 0;
                if(!empty($spm)){
                    foreach($spm->spmd as $row){
                        $jml = $jml + $row->vol;
                    }
                }

            $sppdis_vol_btg = DB::table('SPM_H')
                ->selectRaw('SUM(SPTB_D.VOL) as sppdis_vol_btg')
                ->join('SPTB_H','SPTB_H.NO_SPM','=','SPM_H.NO_SPM')
                ->join('SPTB_D','SPTB_H.NO_SPTB','=','SPTB_D.NO_SPTB')
                ->where('SPM_H.NO_SPPB',$request->no_spp)
                ->groupBy('SPM_H.NO_SPM','SPM_H.NO_SPPB','SPTB_H.NO_SPM','SPTB_D.NO_SPTB')
                ->first();

            $collection_table->push((object)[
                'kode_produk' => $item->produk->kd_produk,
                'type_produk' => $item->produk->kd_produk.' - '.$item->produk->tipe,
                'spp_vol_btg' => $item->app2_vol,
                'spp_vol_ton' => 0,
                'sppdis_vol_btg' => $sppdis_vol_btg->sppdis_vol_btg ?? 0,
                'sppdis_vol_ton' => 0,
                'segmen' => $data_segmen->jml_segmen,
                'spm' => $jml,
                'vol_sppb' => $data_segmen->app2_vol
            ]);
        }


        $no_npp = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $no_spprb = SpprbH::with('pat')->where('no_npp',$no_npp->no_npp)->first();
        $pelanggan = Npp::select('nama_pelanggan','nama_proyek')->where('no_npp',$no_npp->no_npp)->first();
        $vendor_angkutan = Vendor::where('vendor_id','LIKE','WB%')->where('sync_eproc',1)->get();
        $tujuan = Npp::with('infoPasar.region')->where('no_npp', $no_npp->no_npp)->first();

        $jarak = Sp3D::where('no_npp',$no_npp->no_npp)->where('pat_to',$no_spprb->pat->kd_pat)->first();
        if(empty($jarak)){
            $jarak = 0;
        }

        $kondisiPenyerahan = [
            'L' => 'LOKO',
            'F' => 'FRANKO',
            'T' => 'TERPASANG',
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $no_npp->no_npp ? $kondisiPenyerahan[strtoupper(substr($no_npp->no_npp, -1))] : 'LOKO';

        $html = view('pages.spm.box2', [
            'no_npp' => $no_npp->no_npp,
            'no_spprb' => $no_spprb->no_spprb,
            'detail_spp' => $collection_table,
            'no_spp' => $no_spp,
            'vendor_angkutan' => $vendor_angkutan,
            'kp'=> $kondisiPenyerahanDipilih,
            'pelanggan' => $pelanggan->nama_pelanggan,
            'nama_proyek' => $pelanggan->nama_proyek,
            'tujuan' => $tujuan,
            'jarak' => $jarak
        ])->render();

        return response()->json( array('success' => true, 'html'=> $html) );
    }

    function getJmlSegmen(Request $request){
        $no_sppb = $request->no_sppb;
        $kd_produk = $request->kd_produk;

        $data = SppbD::select('jml_segmen','app2_vol')
                ->where('no_sppb',$no_sppb)
                ->where('kd_produk',$kd_produk)
                ->first();

        $spm = SpmH::with(['spmd' => function($sql) use ($kd_produk){
                    $sql->where('kd_produk',$kd_produk);
                }])
                ->where('no_sppb',$no_sppb)
                ->first();

        $collection = new Collection();

        $jml = 0;
        if(!empty($spm)){
            foreach($spm->spmd as $row){
                $jml = $jml + $row->vol;
            }
        }


        $collection->push((object)[
            'jml_segmen' => $data->jml_segmen,
            'app2_vol' => $data->app2_vol,
            'jml_spm' => $jml,
        ]);

        return response()->json($collection);

    }

    public function store(Request $request, FlasherInterface $flasher){
        // return response()->json($request->all());
        try {
            Validator::make($request->all(), [
                'vendor'        => 'required',
            ])->validate();

            DB::beginTransaction();
            // store in SPM_H
            $no_sppb = $request->no_spp;
            $tgl_spm = date('Y-m-d', strtotime($request->tanggal));
            $jns_spm = $request->jenis_spm;

            // ---------
            $no_npp = SppbH::select('no_npp')->where('no_sppb',$no_sppb)->first();
            $no_spprb = SpprbH::with('pat')->where('no_npp',$no_npp->no_npp)->first();
            $pat_to = $no_spprb->kd_pat;
            // -----------

            $vendor_angkutan = $request->vendor;
            $jarak = $request->jarak;

            //create number
            $kd_sbu = substr($request->tipe_produk_select[0], 0, 1);
            $n3 = Sbu::select('singkatan2')->where('kd_sbu',$kd_sbu)->first();
            $n4 = Pat::select('singkatan')->where('kd_pat',$pat_to)->first();
            // end of create number

            $noDokumen = 'SPM/'.$n3->singkatan2.'/'.$n4->singkatan;

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

            $no_spm = $newSequence.'/'.$noDokumen.'/'.date('m').'/'.date('Y');

            $SpmH = new SpmH();
            $SpmH->no_spm = $no_spm;
            $SpmH->no_sppb = $no_sppb;
            $SpmH->vendor_id = $vendor_angkutan;
            $SpmH->tgl_spm = $tgl_spm;
            $SpmH->jns_spm = $jns_spm;
            $SpmH->app1 = 0;
            $SpmH->pat_to = $pat_to;
            $SpmH->jarak_km = $request->jarak;
            $SpmH->created_by = session('TMP_NIP') ?? '12345';
            $SpmH->save();

            // store to smp_d
            $i = 0;
            foreach($request->keterangan_select as $row){
                $produk = explode(' - ', $request->tipe_produk_select[$i]);
                $SpmD = new SpmD();
                $SpmD->no_spm = $no_spm;
                $SpmD->kd_produk = $produk[0];
                $SpmD->vol = $request->volume_produk_select[$i];
                $SpmD->ket = $request->keterangan_select[$i];
                $SpmD->save();
                $i++;
            }
            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
            return redirect()->route('spm.index');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage());
            return redirect()->route('spm.create');
        }

    }

    public function konfirmasiLink($spm){
        $nospm = str_replace("|", "/", $spm);
        $data = SpmH::with('pat')->where('no_spm',$nospm)->first();
    
        return view('pages.spm.konfirmasi', [
            'data' => $data
        ]);
    }
    public function konfirmasi(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = SpmH::find($request->no_spm);
            $data->app1 = 1;
            $data->app1_empid = session('TMP_NIP') ?? '12345';
            $data->app1_jbt = !empty(session('TMP_KDJBT')) ? str_replace("JBT", "", session('TMP_KDJBT')) : '12345';
            $data->app1_date = getNow();
            $data->jalur = !empty($request->jalur)?implode("|", $request->jalur):null;
            $data->save();

            SptbH::whereNoPol($data->no_pol)
                ->whereNull('app_pelanggan')
                ->whereHas('spmh', function($sql){
                    $sql->where('app1', 1);
                })
                ->update([
                    'app_pelanggan' => 1
                ]);

            DB::commit();
            return redirect()->route('spm.index');
        } catch(Exception $e) {
            DB::rollback();
            return redirect()->route('spm.konfirmasi-link', ['spm' => str_replace('/', '|', $request->no_spm)]);
        }
    }

    public function create_konfirmasi_vendor($spm){
        $no_spm = str_replace('|', '/', $spm); // '0002/SPM/PI/PPB-SMT/09/2022';
        $data = SpmH::with('vendor')->where('no_spm',$no_spm)->first();

        $data_ = SppbH::select('no_npp')->where('no_sppb',$data->no_sppb)->first();
        // $data_1 = SpprbH::with('pat')->where('no_npp',$data_->no_npp)->get();
        $detail_spm = SpmH::with('spmd.produk')->where('no_spm',$no_spm)->first();
        // $detail_spp = SppbD::with('produk')->where('no_sppb',$request->no_spp)->get();

        $collection_table = new Collection();
        foreach($detail_spm->spmd as $item){

            $data_segmen = SppbD::select('jml_segmen','app2_vol')
                ->where('no_sppb',$data->no_sppb)
                ->where('kd_produk',$item->kd_produk)
                ->first();


            $sppdis_vol_btg = DB::table('SPM_H')
                ->selectRaw('SUM(SPTB_D.VOL) as sppdis_vol_btg')
                ->join('SPTB_H','SPTB_H.NO_SPM','=','SPM_H.NO_SPM')
                ->join('SPTB_D','SPTB_H.NO_SPTB','=','SPTB_D.NO_SPTB')
                ->where('SPM_H.NO_SPPB',$data->no_sppb)
                ->groupBy('SPM_H.NO_SPM','SPM_H.NO_SPPB','SPTB_H.NO_SPM','SPTB_D.NO_SPTB')
                ->first();

            $collection_table->push((object)[
                'kode_produk' => $item->produk->kd_produk,
                'type_produk' => $item->produk->tipe,
                'spp_vol_btg' => $item->app2_vol,
                'spp_vol_ton' => 0,
                'sppdis_vol_btg' => $sppdis_vol_btg->sppdis_vol_btg ?? 0,
                'sppdis_vol_ton' => 0,
                'segmen' => $data_segmen->jml_segmen,
                'spm' => $item->vol,
                'vol_sppb' => $data_segmen->app2_vol,
                'keterangan' => $item->ket
            ]);
        }


        $no_npp = SppbH::select('no_npp')->where('no_sppb',$data->no_sppb)->first();
        $no_spprb = SpprbH::with('pat')->where('no_npp',$data_->no_npp)->first();
        $pelanggan = Npp::select('nama_pelanggan','nama_proyek')->where('no_npp',$data_->no_npp)->first();
        $vendor_angkutan = Vendor::where('vendor_id',$data->vendor_id)->first();
        $tujuan = Npp::with('infoPasar.region')->where('no_npp',$data_->no_npp)->first();

        $jarak = Sp3D::where('no_npp',$data_->no_npp)->where('pat_to',$no_spprb->pat->kd_pat)->first();
        if(empty($jarak)){
            $jarak = 0;
        }

        $kondisiPenyerahan = [
            'L' => 'LOKO',
            'F' => 'FRANKO',
            'T' => 'TERPASANG',
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($no_spprb->no_npp, -1))];
        $armada = Armada::with('driver')->whereVendorId(Auth::user()->vendor_id)->get();

        return view('pages.spm.konfirmasi-vendor', [
            'data' => $data,
            'collectoin' => $collection_table,
            'no_npp' => $no_npp->no_npp,
            'no_spprb' => $no_spprb->no_spprb,
            'detail_spp' => $collection_table,
            'no_spp' => $data->no_sppb,
            'vendor_angkutan' => $vendor_angkutan,
            'kp'=> $kondisiPenyerahanDipilih,
            'pelanggan' => $pelanggan->nama_pelanggan,
            'nama_proyek' => $pelanggan->nama_proyek,
            'tujuan' => $tujuan,
            'jarak' => $jarak,
            'armada' => $armada
        ]);
    }

    public function store_konfirmasi_vendor(Request $request, FlasherInterface $flasher){
        $no_spm = $request->no_spm;
        $armada = $request->armada;

        $ex = explode('|', $armada);

        $data = SpmH::where('no_spm',$no_spm)->first();
        $data->app2 = 1;
        $data->no_pol = $ex[0];
        $data->app2_name = $ex[1];
        $data->app2_hp = $ex[2];
        $data->save();

        $flasher->addSuccess('Data has been update successfully!');

        return redirect()->route('spm.index');
    }

    public function print($no_spm){
        $no_spm = str_replace('|', '/', $no_spm); // '0350/SPM/I/2008';

        $spmh = SpmH::find($no_spm);

        $logo = File::get(public_path('assets/media/logos/tms.png'));


        $spmh = SpmH::with('spmd', 'sppb')->find($no_spm);
        $sbu = DB::table('tb_sbu')->where('kd_sbu', substr($spmh->spmd->first()->kd_produk, 0, 1))->first();
        $npp = Npp::select('npp.no_npp',
                    'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                    'tb_pat.ket as pat',
                    'tb_pat.kota',
                    'npp.kd_pat')
                ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                ->leftJoin('spnpp', 'spnpp.no_npp', '=', 'npp.no_npp')
                ->where('npp.no_npp', $spmh->sppb->no_npp)
                ->first();

        $logo = File::get(public_path('assets/media/logos/wikabeton.jpg'));

        $logo = base64_encode($logo);

        // return response()->json($npp);

        return Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            // ->loadView('pages.spm.print', ['spmh' => $spmh, 'logo' => $logo])->stream('Surat Permintaan Muat.pdf');
            ->loadView('pages.spm.print', ['spmh' => $spmh, 'logo' => $logo, 'sbu' => $sbu, 'npp' => $npp])->stream('Surat Permintaan Muat.pdf');
    }

    public function show($spm){
        $nospm = str_replace("|", "/", $spm);
        $data = SpmH::with('pat')->where('no_spm',$nospm)->first();
        return view('pages.spm.show', [
            'data' => $data
        ]);
    }

    public function edit($spm){
        $nospm = str_replace("|", "/", $spm);
        $data = SpmH::with('pat')->where('no_spm',$nospm)->first();
        return view('pages.spm.edit', [
            'data' => $data
        ]);
    }

    public function getDataEditBox2(Request $request){

        $no_spp = $request->no_spp;
        $no_spm = $request->no_spm;

        $detail_spp = SppbD::with('produk','spmd')->where('no_sppb',$request->no_spp)->get();

        $collection_table = new Collection();
        foreach($detail_spp as $item){

            $data_segmen = SppbD::select('jml_segmen','app2_vol')
                ->where('no_sppb',$no_spp)
                ->where('kd_produk',$item->produk->kd_produk)
                ->first();

            $spm = SpmH::with(['spmd' => function($sql) use ($item){
                    $sql->where('kd_produk',$item->produk->kd_produk);
                }])
                ->where('no_sppb',$no_spp)
                ->first();
                $jml = 0;
                if(!empty($spm)){
                    foreach($spm->spmd as $row){
                        $jml = $jml + $row->vol;
                    }
                }

            $sppdis_vol_btg = DB::table('SPM_H')
                ->selectRaw('SUM(SPTB_D.VOL) as sppdis_vol_btg')
                ->join('SPTB_H','SPTB_H.NO_SPM','=','SPM_H.NO_SPM')
                ->join('SPTB_D','SPTB_H.NO_SPTB','=','SPTB_D.NO_SPTB')
                ->where('SPM_H.NO_SPPB',$request->no_spp)
                ->groupBy('SPM_H.NO_SPM','SPM_H.NO_SPPB','SPTB_H.NO_SPM','SPTB_D.NO_SPTB')
                ->first();

            $spmd = SpmD::where('no_spm', $no_spm)->where('kd_produk',$item->produk->kd_produk)->first();

            $collection_table->push((object)[
                'kode_produk' => $item->produk->kd_produk,
                'type_produk' => $item->produk->kd_produk.' - '.$item->produk->tipe,
                'spp_vol_btg' => $item->app2_vol,
                'spp_vol_ton' => 0,
                'sppdis_vol_btg' => $sppdis_vol_btg->sppdis_vol_btg ?? 0,
                'sppdis_vol_ton' => 0,
                'segmen' => $data_segmen->jml_segmen,
                'spm' => $jml,
                'vol_sppb' => $data_segmen->app2_vol,
                'vol' => $spmd->vol ?? 0,
                'ket' => $spmd->ket ?? null
            ]);
        }


        $no_npp = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $no_spprb = SpprbH::with('pat')->where('no_npp',$no_npp->no_npp)->first();
        $pelanggan = Npp::select('nama_pelanggan','nama_proyek')->where('no_npp',$no_npp->no_npp)->first();
        $vendor_angkutan = Vendor::where('vendor_id','LIKE','WB%')->where('sync_eproc',1)->get();
        
        $data_spm = SpmH::with('vendor', 'sppb')->where('no_spm',$no_spm)->first();
        $tujuan = Npp::with('infoPasar.region')->where('no_npp', $data_spm->sppb->no_npp)->first();

        $jarak = $data_spm->jarak_km;
        $jalur = DB::table('oee_ref_jalur_h')
            ->where('kode_pat', $data_spm->pat_to)
            ->get()
            ->mapWithKeys(function($item){
                return [$item->jalur => $item->jalur .'. '.$item->fungsi_jalur];
            })
            ->toArray();


        $kondisiPenyerahan = [
            'L' => 'LOKO',
            'F' => 'FRANKO',
            'T' => 'TERPASANG',
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $no_npp->no_npp ? $kondisiPenyerahan[strtoupper(substr($no_npp->no_npp, -1))] : 'LOKO';

        $html = view('pages.spm.edit-box2', [
            'no_npp' => $no_npp->no_npp,
            'no_spprb' => $no_spprb->no_spprb,
            'detail_spp' => $collection_table,
            'no_spp' => $no_spp,
            'vendor_angkutan' => $vendor_angkutan,
            'kp'=> $kondisiPenyerahanDipilih,
            'pelanggan' => $pelanggan->nama_pelanggan ?? null,
            'nama_proyek' => $pelanggan->nama_proyek ?? null,
            'tujuan' => $tujuan,
            'jarak' => $jarak,
            'jalur' => $jalur,
            'selected_vendor_id' => $data_spm->vendor_id,
            'selected_vendor_name' => $data_spm->vendor->nama,
            'source' => $request->source,
        ])->render();

        return response()->json( array('success' => true, 'html'=> $html) );
    }

    public function store_edit(Request $request, FlasherInterface $flasher){
        try {
            Validator::make($request->all(), [
                'vendor'        => 'required',
            ])->validate();

            DB::beginTransaction();

            $no_spm = $request->no_spm;
            $vendor_angkutan = $request->vendor;

            $SpmH = SpmH::where('no_spm',$no_spm)->first();
            $SpmH->vendor_id = $vendor_angkutan;
            $SpmH->last_update_by = session('TMP_NIP') ?? '12345';
            $SpmH->last_update_date = date('Y-m-d H:i:s');
            $SpmH->save();

            // store to smp_d
            $i = 0;
            foreach($request->keterangan_select as $row){
                $produk = explode(' - ', $request->tipe_produk_select[$i]);
                $SpmD = SpmD::where('no_spm', $no_spm)->where('kd_produk', $produk[0])->first();
                if($request->volume_produk_select[$i] > 0){
                    $SpmD->vol = $request->volume_produk_select[$i];
                    $SpmD->ket = $request->keterangan_select[$i];
                    $SpmD->save();
                }
                $i++;
            }
            DB::commit();

            $flasher->addSuccess('Data has been updated successfully!');
            return redirect()->route('spm.index');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage());
            return redirect()->route('spm.create');
        }
    }

    public function armadaTibaValidation(Request $request){
        $now = date('d/m/Y');
        // $now = '15/11/2022';
        $active_week = DB::select("select  WOS.\"FNC_GETMG\" (to_Date('" . $now . "','dd/mm/yyyy'), '1A') minggu from dual")[0]->minggu;
        $year = date('Y');
        $spm = SpmH::find($request->no_spm);
        $rating = ArmadaRating::whereTahun($year)->whereMinggu($active_week)->whereNopol($spm->no_pol)->first();
        return response()->json(['filled' => $rating ? true : false]);
    }
    
    public function armadaTiba(Request $request){
        // $now = '15/11/2022';
        $now = date('d/m/Y');
        $year = date('Y');
        try {
            DB::beginTransaction();
            if($request->type == 'without-form'){
                $spm = SpmH::find($request->no_spm);
                // $spm->waktu_datang = DB::raw("TO_DATE('" . . "', 'YYYY-MM-DD HH24:MI:SS')")
                $spm->waktu_datang = date('Y-m-d H:i:s');
                $spm->save();
            }else{
                $active_week = DB::select("select  WOS.\"FNC_GETMG\" (to_Date('" . $now . "','dd/mm/yyyy'), '1A') minggu from dual")[0]->minggu;
                $blth = DB::select("select  WOS.\"FNC_GETBL\" (to_Date('" . $now . "','dd/mm/yyyy')) bulan from dual")[0]->bulan;
                $spm = SpmH::find($request->no_spm);
                $spm->waktu_datang = date('Y-m-d H:i:s');
                $spm->save();
                
                $rating = new ArmadaRating;
                $rating->tahun = $year;
                $rating->minggu = $active_week;
                $rating->blth = $blth;
                $rating->no_spm = $spm->no_spm;
                $rating->nopol = $spm->no_pol;
                $rating->driver_name = $spm->app2_name;
                $rating->driver_hp = $spm->app2_hp;
                $rating->save();
                
                $criterias = ArmadaCriteria::all();
                foreach ($criterias as $criteria) {
                    $var = $criteria->code;
                    $rating_ = new ArmadaRatingDetail;
                    $rating_->ar_id = $rating->id;
                    $rating_->criteria = implode('|', [$criteria->criteria, $criteria->description]);
                    $rating_->bobot = $request->$var ?? '0';
                    $rating_->save();
                }
            }
            return response()->json(['success' => true]);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
