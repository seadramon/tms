<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\SptbD;
use App\Models\SptbD2;
use App\Models\SppbD;
use App\Models\MsNoDokumen;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

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
                $query->whereHas('npp', function($sql){
                    $sql->where('kd_pat', session('TMP_KDWIL'));
                });
            }
        }

        return DataTables::eloquent($query)
            ->editColumn('tgl_sptb', function ($model) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_sptb)->format('d-m-Y');
            })
            ->addColumn('menu', function ($model) {
                $list = '';
                if(Auth::check()){
                    
                }else{
                    $action = json_decode(session('TMS_ACTION_MENU'));
                    if(in_array('edit', $action)){
                        $list .= '<li><a class="dropdown-item" href="' . route('sptb.edit', str_replace('/', '|', $model->no_sptb)) . '">Edit</a></li>';
                    }
                    if(in_array('konfirmasi', $action)){
                        $list .= '<li><a class="dropdown-item set-konfirmasi" href="javascript:void(0)" data-id="'. $model->no_sptb .'">Konfirmasi</a></li>';
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
            ->rawColumns(['menu'])
            ->toJson();
    }
    
    public function create(Request $request)
    {
        $no_spm = SpmH::pluck('no_spm', 'no_spm')->toArray();
            
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
        $spmH = SpmH::with(['sppbh', 'vendor', 'pat', 'spmd.produk'])
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
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'no_spm'        => 'required'
            ])->validate();

            $spmH = SpmH::find($request->no_spm);

            $noDokumen = 'SPtB/2E/05';

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

            $noSptb = $newSequence . '/SPtB/' . ($spmH->pat_to ?? '2E') . '/' . substr($month[0]->month, 0, 2) . '/' . date('Y');

            $kdPat = session("TMP_KDWIL") ?? '1A';

            $sptbH = new SptbH();
            $sptbH->no_spm = $request->no_spm;
            $sptbH->jns_sptb = $request->jns_sptb;
            $sptbH->tgl_berangkat = DB::raw("TO_DATE(('".date('Y-m-d', strtotime($request->tgl_berangkat))."'), 'YYYY-MM-DD')");
            $sptbH->ket = $request->ket;
            $sptbH->tujuan = $request->tujuan;
            $sptbH->angkutan = $request->angkutan;
            $sptbH->no_pol = $request->no_pol;
            $sptbH->nama_driver = $request->nama_driver;
            $sptbH->no_hp_driver = $request->no_hp_driver;
            $sptbH->jarak_km = $request->jarak_km;
            $sptbH->no_sptb = $noSptb;
            $sptbH->tgl_sptb = date('Y-m-d');
            $sptbH->no_spprb = $spmH->sppbh?->no_spprb;
            $sptbH->no_npp = $spmH->no_npp;
            $sptbH->app_driver = 0;
            $sptbH->barcode_img = decbin(ord($noSptb));
            $sptbH->kd_pat = $kdPat;
            $sptbH->created_by = session('TMP_NIP') ?? '12345';
            $sptbH->created_date = date('Y-m-d H:i:s');
            $sptbH->save();

            $j = 0;

            for($i=0; $i < count($request->kd_produk); $i++){
                $sppbD = SppbD::where('no_sppb', $spmH->no_sppb)
                    ->where('kd_produk', $request->kd_produk[$i])
                    ->first();

                $sptbD = new SptbD();
                $sptbD->no_sptb = $noSptb;
                $sptbD->kd_produk = $request->kd_produk[$i];
                $sptbD->vol = $sppbD->segmental == 1 ? ($request->vol[$i] / $sppbD->jml_segmen) : $request->vol[$i];
                $sptbD->save();

                for($j; $j < $request->vol[$i]; $j++){
                    $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
                        ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
                        ->first();

                    $lasttrxidnum   = $maxTrxid->max_trxid ?? 0;
                    $n2 = str_pad($lasttrxidnum + 1, 6, 0, STR_PAD_LEFT);

                    $sptbD2 = new SptbD2();
                    $sptbD2->no_sptb = $noSptb;
                    $sptbD2->kd_produk = $request->kd_produk[$i];
                    $sptbD2->tgl_produksi = DB::raw("TO_DATE(('".date('Y-m-d', strtotime($request->child_tgl_produksi[$j]))."'), 'YYYY-MM-DD')");
                    $sptbD2->stockid = $request->child_kd_produk[$j];
                    $sptbD2->vol = 1;
                    $sptbD2->kd_pat = $kdPat;
                    $sptbD2->trxid_tpd2 = 'TRX.' . $kdPat . '.00.' . date('Y') . '.' . date('m') . '.' . ($n2);
                    $sptbD2->trxid = 'TRX.' . $kdPat . '.SPTBD2.' . date('Y') . '.' . date('m') . '.' . ($n2);
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

    public function edit($no_sptb)
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

        return view('pages.sptb.edit', compact(
            'data', 'no_spm', 'jns_sptb'
        ));
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

            SptbD::where('no_sptb', $no_sptb)->delete();
            SptbD2::where('no_sptb', $no_sptb)->delete();

            $j = 0;
            
            for($i=0; $i < count($request->kd_produk); $i++){
                for($j; $j < $request->vol[$i]; $j++){
                    $maxTrxid = SptbD2::selectRaw('max(substr(trxid_tpd2,23,6)) as MAX_TRXID')
                        ->where(DB::raw('substr(trxid,15,4)'), date('Y'))
                        ->first();

                    $lasttrxidnum   = $maxTrxid->max_trxid ?? 0;
                    $n2 = str_pad($lasttrxidnum + 1, 6, 0, STR_PAD_LEFT);

                    $sptbD2 = new SptbD2();
                    $sptbD2->no_sptb = $no_sptb;
                    $sptbD2->kd_produk = $request->kd_produk[$i];
                    $sptbD2->tgl_produksi = DB::raw("TO_DATE(('".date('Y-m-d', strtotime($request->child_tgl_produksi[$j]))."'), 'YYYY-MM-DD')");
                    $sptbD2->stockid = $request->child_kd_produk[$j];
                    $sptbD2->vol = 1;
                    $sptbD2->kd_pat = $kdPat;
                    $sptbD2->trxid_tpd2 = 'TRX.' . $kdPat . '.00.' . date('Y') . '.' . date('m') . '.' . ($n2);
                    $sptbD2->trxid = 'TRX.' . $kdPat . '.SPTBD2.' . date('Y') . '.' . date('m') . '.' . ($n2);
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
}