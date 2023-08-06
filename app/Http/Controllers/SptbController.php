<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\SptbD;
use App\Models\SptbD2;
use App\Models\SppbD;
use App\Models\Pat;
use App\Models\MsNoDokumen;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SptbController extends Controller
{
    public function index()
    {
        return view('pages.sptb.index');
    }

    public function data()
    {
        $query = SptbH::with(['spmh', 'npp'])->select('*');
        if(Auth::check()){
            $query->whereHas('spmh', function($sql){
                $sql->whereVendorId(Auth::user()->vendor_id);
            });
        }else{
            if(session('TMP_KDWIL') != '0A'){
                $query->where('sptb_h.kd_pat', session('TMP_KDWIL'));
            }
        }

        return DataTables::eloquent($query)
            ->editColumn('tgl_sptb', function ($model) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_sptb)->format('d-m-Y');
            })
            ->editColumn('tgl_berangkat', function ($model) {
                return date('d-m-Y', strtotime($model->tgl_berangkat));
            })
            ->addColumn('menu', function ($model) {
                $list = '';
                if(Auth::check()){
                    $list .= '<li><a class="dropdown-item" href="' . route('sptb.show', str_replace('/', '|', $model->no_sptb)) . '">View</a></li>';
                    /*$list .= '<li><a class="dropdown-item" href="http://10.3.1.80/genreport/genreport.asp?RptName=sptb2020.rpt&fparam='.$model->no_sptb.'&ftype=5&keyId=OS">Print</a></li>';*/
                    $list .= '<li><a class="dropdown-item" href="' . route('sptb.print', str_replace('/', '|', $model->no_sptb)) . '">Print</a></li>';
                }else{
                    $action = json_decode(session('TMS_ACTION_MENU'));
                    if(in_array('edit', $action) && $model->app_pelanggan != '1' && in_array($model->no_pol, [null, ""])){
                        $list .= '<li><a class="dropdown-item" href="' . route('sptb.edit', str_replace('/', '|', $model->no_sptb)) . '">Edit</a></li>';
                    }
                    if(in_array('view', $action)){
                        $list .= '<li><a class="dropdown-item" href="' . route('sptb.show', str_replace('/', '|', $model->no_sptb)) . '">View</a></li>';
                    }
                    if(in_array('konfirmasi', $action)){
                        $list .= '<li><a class="dropdown-item set-konfirmasi" href="javascript:void(0)" data-id="'. $model->no_sptb .'">Konfirmasi</a></li>';
                    }
                    if(in_array('print', $action)){
                        // $list .= '<li><a class="dropdown-item" href="http://10.3.1.80/genreport/genreport.asp?RptName=sptb2020.rpt&fparam='.$model->no_sptb.'&ftype=5&keyId=OS">Print Test</a></li>';
                        $list .= '<li><a class="dropdown-item" href="' . route('sptb.print', str_replace('/', '|', $model->no_sptb)) . '">Print</a></li>';
                    }
                    if($model->app_pelanggan == '1' && in_array('penilaian_mutu', $action)){
                        $list .= '<li><a class="dropdown-item" href="' . route('sptb.penilaian-mutu', str_replace('/', '|', $model->no_sptb)) . '">Penilaian Mutu</a></li>';
                    }
                    if($model->app_pelanggan == '1' && in_array('penilaian_pelayanan', $action)){
                        $list .= '<li><a class="dropdown-item penilaian-pelayanan" href="javascript:void(0)" data-sptb="' . $model->no_sptb . '">Penilaian Pelayanan</a></li>';
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
            ->addColumn('status', function($model) {
                $teks = '';
                if($model->app_pelanggan == "1"){
                    $teks = '<span class="badge badge-light-success mr-2 mb-2">Received</i></span>';
                }else{
                    $teks = '<span class="badge badge-light-warning mr-2 mb-2">onProgress</i></span>';
                }
                return $teks;
            })
            ->rawColumns(['menu', 'status'])
            ->toJson();
    }
    
    public function create(Request $request)
    {
        if(session('TMP_KDWIL') != '0A'){
            $no_spm = SpmH::doesntHave('sptbh')->where('tgl_spm', '>=', date('Y-m-d 00:00:00', strtotime('-1 years')))->where('pat_to', session('TMP_KDWIL'))->pluck('no_spm', 'no_spm')->toArray();
        }else{
            $no_spm = SpmH::doesntHave('sptbh')->where('tgl_spm', '>=', date('Y-m-d 00:00:00', strtotime('-1 years')))->pluck('no_spm', 'no_spm')->toArray();
        }
            
        $no_spm = ["" => "Pilih No. SPM"] + $no_spm;

        $jns_sptb =  [
            '2' => 'Stok Titipan', 
            '0' => 'Stok Aktif'
        ];

        $jns_sptb = ["" => "Pilih Jenis SPTB"] + $jns_sptb;
        $spm = $request->has('spm') ? str_replace('|', '/', $request->spm) : null;

        return view('pages.sptb.create', compact(
            'no_spm', 'jns_sptb', 'spm'
        ));
    }

    public function getSpm(Request $request)
    {
        $spmH = SpmH::with(['sppbh.npp.infoPasar.region', 'vendor', 'pat', 'spmd.produk'])
            ->where('no_spm', $request->no_spm)
            ->first();

        $volume = [];

        // $spmH->spmd->map(function ($spmd) use (&$volume, $spmH) {
        //     $sppbD = SppbD::where('no_sppb', $spmH->no_sppb)
        //         ->where('kd_produk', $spmd->kd_produk)
        //         ->first();
            
        //     return $volume[] = $sppbD->segmental == 1 ? ($spmd->vol / $sppbD->jml_segmen) : $spmd->vol;
        // });

        return [
            'volume' => $volume,
            'spm' => $spmH
        ];
    }

    public function getVolume(Request $request)
    {
        $sppbD = SppbD::where('no_sppb', $request->no_sppb)
            ->where('kd_produk', $request->kd_produk)
            ->first();
    
        return $sppbD->segmental == 1 ? ($request->volume / $sppbD->jml_segmen) : $request->volume;
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $kdPat = session("TMP_KDWIL") ?? '1A';
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'no_spm'        => 'required'
            ])->validate();

            $spmH = SpmH::with('sppbh')->find($request->no_spm);
            $active_bl = DB::select("select  WOS.\"FNC_GETBL\" (to_Date('" . date('d/m/Y') . "','dd/mm/yyyy')) bulan from dual")[0]->bulan;
            $month = DB::select("select fnc_getbl(to_date(sysdate)) as month from dual");
            $noDokumen = 'SPtB/'.$kdPat.'/'. substr($month[0]->month, 0, 2);

            $msNoDokumen = MsNoDokumen::where('tahun', date('Y'))->where('no_dokumen', $noDokumen);
            
            if($msNoDokumen->exists()){
                $msNoDokumen = $msNoDokumen->first();

                $newSequence = sprintf('%04s', ((int)$msNoDokumen->seq + 1));

                $msNoDokumen->update([
                    'seq'           => $newSequence,
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

            $month = DB::select("select fnc_getbl(to_date(sysdate)) as month from dual");

            $noSptb = $newSequence . '/' . $noDokumen . '/' . date('Y');


            $sptbH = new SptbH();
            $sptbH->no_spm = $request->no_spm;
            $sptbH->jns_sptb = $request->jns_sptb;
            $sptbH->tgl_berangkat = DB::raw("TO_DATE(('".date('Y-m-d', strtotime($request->tgl_berangkat))."'), 'YYYY-MM-DD')"); //jam_berangkat
            $sptbH->jam_berangkat = $request->jam_berangkat;
            $sptbH->ket = $request->ket;
            $sptbH->tujuan = $request->tujuan;
            $sptbH->angkutan = $request->angkutan;
            $sptbH->no_pol = $request->no_pol;
            $sptbH->nama_driver = $request->nama_driver;
            $sptbH->no_hp_driver = $request->no_hp_driver;
            $sptbH->jarak_km = $request->jarak_km;
            $sptbH->no_sptb = $noSptb;
            $sptbH->tgl_sptb = date('Y-m-d');
            $sptbH->no_spprb = $spmH->sppbh->no_spprb ?? null;
            $sptbH->no_npp = $spmH->sppbh->no_npp ?? null;
            $sptbH->app_driver = 0;
            $sptbH->app_pelanggan = 0;
            $sptbH->barcode_img = decbin(ord($noSptb));
            $sptbH->kd_pat = $kdPat;
            $sptbH->created_by = session('TMP_NIP') ?? '12345';
            $sptbH->created_date = date('Y-m-d H:i:s');
            $sptbH->save();

            $j = 0;

            $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
                            ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
                            ->first();
            $lasttrxidnum = $maxTrxid->max_trxid ?? 0;
            $counter = 0;
            for($i=0; $i < count($request->kd_produk); $i++){
                $sppbD = SppbD::where('no_sppb', $spmH->no_sppb)
                    ->where('kd_produk', $request->kd_produk[$i])
                    ->first();

                $sptbD = new SptbD();
                $sptbD->no_sptb = $noSptb;
                $sptbD->kd_produk = $request->kd_produk[$i];
                $sptbD->vol = $sppbD->segmental == 1 ? ($request->vol[$i] / $sppbD->jml_segmen) : $request->vol[$i];
                // $sptbD->vol = $request->vol[$i];
                $sptbD->save();

                for($j=0; $j < $request->vol[$i]; $j++){
                // for($j; $j < $request->vol[$i]; $j++){
                    // $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
                    //         ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
                    //         ->first();
                    // $lasttrxidnum = $maxTrxid->max_trxid ?? 0;
                    $n2 = str_pad($lasttrxidnum + 1, 6, 0, STR_PAD_LEFT);

                    $sptbD2 = new SptbD2();
                    $sptbD2->no_sptb = $noSptb;
                    $sptbD2->kd_produk = $request->kd_produk[$i];
                    $sptbD2->tgl_produksi = DB::raw("TO_DATE('".date('Y-m-d', strtotime($request->child_tgl_produksi[$counter]))."', 'YYYY-MM-DD')");
                    $sptbD2->stockid = $request->child_kd_produk[$counter];
                    $sptbD2->vol = 1;
                    $sptbD2->kd_pat = $kdPat;
                    // $sptbD2->trxid_tpd2 = intval($lasttrxidnum) + 1;
                    $sptbD2->trxid_tpd2 = 'TRX.' . $kdPat . '.00.' . date('Y') . '.' . date('m') . '.' . $n2;
                    $sptbD2->trxid = 'TRX.' . $kdPat . '.SPTBD2.' . date('Y') . '.' . date('m') . '.' . $n2;
                    $sptbD2->save();
                    $lasttrxidnum++;
                    $counter++;
                }
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sptb.index');
    }

    public function show($no_sptb)
    {
        $data = SptbH::with('ppb_muat')->find(str_replace('|', '/', $no_sptb));
        
        $no_spm = SpmH::where('app2', 1)
            ->pluck('no_spm', 'no_spm')
            ->toArray();
            
        $no_spm = ["" => "Pilih No. SPM"] + $no_spm;

        $jns_sptb =  [
            '2' => 'Stok Titipan', 
            '0' => 'Stok Aktif'
        ];

        $jns_sptb = ["" => "Pilih Jenis SPTB"] + $jns_sptb;

        return view('pages.sptb.show', compact(
            'data', 'no_spm', 'jns_sptb'
        ));
    }

    public function edit($no_sptb)
    {
        $data = SptbH::with('sptbd2')->find(str_replace('|', '/', $no_sptb));
        
        $no_spm = SpmH::where('app2', 1)
            ->pluck('no_spm', 'no_spm')
            ->toArray();
            
        $no_spm = ["" => "Pilih No. SPM"] + $no_spm;

        $jns_sptb =  [
            '2' => 'Stok Titipan', 
            '0' => 'Stok Aktif'
        ];

        $jns_sptb = ["" => "Pilih Jenis SPTB"] + $jns_sptb;

        return view('pages.sptb.edit', compact(
            'data', 'no_spm', 'jns_sptb'
        ));
    }
    
    public function penilaianMutu($no_sptb)
    {
        $data = SptbH::find(str_replace('|', '/', $no_sptb));
        
        $no_spm = SpmH::where('app2', 1)
            ->pluck('no_spm', 'no_spm')
            ->toArray();
            
        $no_spm = ["" => "Pilih No. SPM"] + $no_spm;

        $jns_sptb =  [
            '2' => 'Stok Titipan', 
            '0' => 'Stok Aktif'
        ];

        $jns_sptb = ["" => "Pilih Jenis SPTB"] + $jns_sptb;

        return view('pages.sptb.konfirmasi_produk', compact(
            'data', 'no_spm', 'jns_sptb'
        ));
    }

    public function penilaianMutuSimpan(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'no_sptb'        => 'required'
            ])->validate();

            $kdPat = session("TMP_KDWIL") ?? '1A';

            $j = 0;
            
            $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
                ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
                ->first();
            $lasttrxidnum   = $maxTrxid->max_trxid ?? 0;

            for($i=0; $i < count($request->kd_produk); $i++){
                foreach ($request->child_trxid_tpd2[$request->kd_produk[$i]] as $j => $item) {
                    $sptbD2 = SptbD2::where('trxid_tpd2', $item)->where('no_sptb', $request->no_sptb)->whereKdProduk($request->kd_produk[$i])->first();
                    $sptbD2->kondisi_produk = $request->child_kondisi_produk[$request->kd_produk[$i]][$j];
                    $sptbD2->save();
                }
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError('TES' + $e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sptb.index');
    }
    
    public function penilaianPelayananSimpan(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
            $sptb = SptbH::find($request->no_sptb);
            $sptb->nilai_pelayanan = $request->layanan;
            $sptb->save();

            DB::commit();

            return response()->json(['success' => true]);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, FlasherInterface $flasher, $no_sptb)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'no_sptb'        => 'required'
            ])->validate();

            $no_sptb = str_replace('|', '/', $no_sptb);

            $kdPat = session("TMP_KDWIL") ?? '1A';

            // SptbD2::where('no_sptb', $no_sptb)->delete();

            $j = 0;
            
            // $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
            //     ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
            //     ->first();
            // $lasttrxidnum   = $maxTrxid->max_trxid ?? 0;

            for($i=0; $i < count($request->kd_produk); $i++){
                for($j; $j < $request->vol[$i]; $j++){
                    // $lasttrxidnum = $lasttrxidnum + 1;
                    // $n2 = str_pad($lasttrxidnum, 6, 0, STR_PAD_LEFT);

                    $sptbD2 = new SptbD2();
                    $sptbD2->no_sptb = $no_sptb;
                    $sptbD2->kd_produk = $request->kd_produk[$i];
                    $sptbD2->tgl_produksi = DB::raw("TO_DATE(('".date('Y-m-d', strtotime($request->child_tgl_produksi[$j]))."'), 'YYYY-MM-DD')");
                    $sptbD2->stockid = $request->child_kd_produk[$j];
                    $sptbD2->vol = 1;
                    $sptbD2->kd_pat = $kdPat;
                    // $sptbD2->trxid_tpd2 = 'TRX.' . $kdPat . '.00.' . date('Y') . '.' . date('m') . '.' . ($n2);
                    // $sptbD2->trxid = 'TRX.' . $kdPat . '.SPTBD2.' . date('Y') . '.' . date('m') . '.' . ($n2);
                    $sptbD2->save();
                }
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sptb.index');
    }

    public function setKonfirmasi(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = SptbH::find($request->id);
            $data->app_pelanggan = 1;
            $data->save();

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch(Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'failed']);
        }
    }

    public function print($noSptb)
    {
        $noSptb = str_replace('|', '/', $noSptb);
        $ppb = null;

        $data = sptbH::find($noSptb);

        // get ppb
        $trxid = !empty($data->trxid)?$data->trxid:null;
        if ($trxid) {
            $arr = explode("-", $trxid);
            $pat_ppb = Pat::where('kd_pat', $arr[1])->first();
            $ppb = $pat_ppb->ket;
        }

        $sptbd2 = SptbD2::where('no_sptb', $noSptb)->get()->groupBy('kd_produk');

        // $detail2 = [];
        // if (count($sptbd2) > 0) {
        //     foreach ($sptbd2 as $row) {
        //         $detail2[$row->kd_produk] = [
        //             'stockid' => $row->stockid,
        //             'tgl' => $row->tgl_produksi
        //         ];
        //     }
        // }
        // return response()->json(full_url_from_path($data->penerima_ttd ?? 'penerima_ttd.jpg'));

        $pdf = Pdf::loadView('prints.sptb', [
            'data' => $data,
            'ppb' => $ppb,
            'sptbd2' => $sptbd2,
        ]);

        $filename = "SPTB-Report";

        return $pdf->setPaper('a4', 'portrait')
            ->stream($filename . '.pdf');
    }
}