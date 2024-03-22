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
use App\Models\Bapp;
use App\Models\BappD;
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

        if(Auth::check()){
            $vendor = Vendor::where('vendor_id', Auth::user()->vendor_id)->get()->pluck('nama', 'vendor_id')->toArray();
            $vendor_id = $vendor;
        }else{
            $vendor = Vendor::where('sync_eproc', 1)->where('vendor_id', 'like', 'WB%')->get()->pluck('nama', 'vendor_id')->toArray();
            $vendor_id = $labelSemua + $vendor;
        }

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

        return view('pages.bapp.index', compact(
            'pat', 'periode', 'status', 'rangeCutOff', 'monthCutOff', 'vendor_id'
        ));
    }

    public function data(Request $request)
    {
        //
        $query = Bapp::with('vendor')->select('*');

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
        if($request->vendor){
            $query->where('vendor_id', $request->vendor_id);
        }

        if(Auth::check()){
            $query->where('bapp_h1.vendor_id', Auth::user()->vendor_id);
        }

        return DataTables::eloquent($query)
                ->editColumn('tgl_bapp', function ($model) {
                    return date('d-m-Y', strtotime($model->tgl_bapp));
                })
                ->addColumn('status', function ($model) {
                    $teks = '';
                    // if(Auth::check()){
                    //     if($model->app2 == 1){
                    //         $teks .= '<span class="badge badge-light-success mr-2 mb-2">Confirmed&nbsp;<i class="fas fa-check text-success"></i></span>';
                    //     }else{
                    //         $teks .= '<span class="badge badge-light-warning mr-2 mb-2">To Be Confirmed</i></span>';
                    //     }
                    // }else{
                    //     if($model->app1 == 1){
                    //         $teks .= '<span class="badge badge-light-success mr-2 mb-2">MUnit&nbsp;<i class="fas fa-check text-success"></i></span>';
                    //     }
                    //     if($model->app2 == 1){
                    //         $teks .= '<span class="badge badge-light-success mr-2 mb-2">Vendor&nbsp;<i class="fas fa-check text-success"></i></span>';
                    //     }
                    // }
                    return $teks;
                })

                ->addColumn('menu', function ($model) {
                    $list = '';
                    $list .= '<li><a class="dropdown-item" href="' . route('spk.show', str_replace('/', '|', $model->no_bapp)) . '">View</a></li>';
                    // $list .= '<li><a class="dropdown-item" href="' . route('spk.edit', str_replace('/', '|', $model->no_bapp)) . '">Edit</a></li>';
                    // $list .= '<li><a class="dropdown-item" href="' . route('spk.print-pdf', str_replace('/', '|', $model->no_bapp)) . '">Print PDF</a></li>';

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

    public function store(Request $request, FlasherInterface $flasher)
    {
        // return response()->json($request->all());
        try {
            DB::beginTransaction();

            Validator::make($request->all(), [
                'no_npp'        => 'required',
                'sp3'     => 'required',
                'pihak_pertama'     => 'required',
            ])->validate();

            // $vendor = Vendor::find($request->vendor_id);

            $noDokumen = 'TP.03.01/WB-' . (session('TMP_KDWIL') ?? '1A');

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

            $no_bapp = $noDokumen . '.' . $newSequence . '/' . date('Y') . '';
            $no_npp = explode(' | ', $request->no_npp);

            $bapp = new Bapp;
            $bapp->no_bapp = $no_bapp;
            $bapp->no_sp3 = $request->sp3;
            $bapp->tgl_bapp = date('Y-m-d', strtotime($request->tgl_bapp));
            $bapp->created_by = Auth::check() ? Auth::user()->id : 1;
            $bapp->catatan = $request->catatan;
            $bapp->jumlah = str_replace(',', '', $request->jumlah);
            $bapp->vendor_id = Auth::check() ? Auth::user()->vendor_id : 'WBI075';
            $bapp->pihak1 = $request->pihak_pertama;
            $bapp->pihak2 = $request->pihak_kedua;
            $bapp->pihak2_jabatan = $request->pihak_kedua_jabatan;
            $bapp->save();

            foreach($request->sp3_produk as $produk){
                $bapp_d = new BappD;
                $bapp_d->no_bapp = $bapp->no_bapp;
                $bapp_d->no_sp3 = $request->sp3;
                $bapp_d->kd_produk = $produk;
                $bapp_d->satuan = $request->sp3_satuan[$produk];
                $bapp_d->sp3_vol_btg = $request->sp3_btg[$produk];
                $bapp_d->sp3_vol_ton = $request->sp3_ton[$produk];
                $bapp_d->harsat = $request->sp3_harsat[$produk];
                $bapp_d->lalu_vol_btg = $request->lalu_btg[$produk];
                $bapp_d->lalu_vol_ton = $request->lalu_ton[$produk];
                $bapp_d->vol_btg = $request->saatini_btg[$produk];
                $bapp_d->vol_ton = $request->saatini_ton[$produk];
                $bapp_d->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('bapp.index');
    }
}
