<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\TrMaterial;
use App\Models\Vendor;
use App\Models\Npp;
use App\Models\PricelistAngkutanH;
use App\Models\PricelistAngkutanD;
use App\Models\PricelistAngkutanD2;
use App\Imports\PricelistImport;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;

class PricelistAngkutanController extends Controller
{   
    public function index()
    {
        return view('pages.pricelist-angkutan.index');
    }

    public function data()
    {
        $query = PricelistAngkutanH::with(['pad' => function($sql) { $sql->with('angkutan'); }, 'pat'])->select('*');

        return DataTables::eloquent($query)
            ->addColumn('angkutan', function ($model) {
                $item = $model->pad->unique('kd_material')->map(function($d){ return $d->angkutan->name; });
                return implode("<br>", $item->all());
            })
            ->addColumn('pemuatan', function ($model) {
                $item = $model->pad->unique('kd_muat')->map(function($d){ return $d->unit_muat; });
                return implode("<br>", $item->all());
            })
            ->addColumn('menu', function ($model) {
                $edit = '<div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="' . route('pricelist-angkutan.show', str_replace('/', '|', $model->id)) . '">View</a></li>
                            <li><a class="dropdown-item" href="' . route('pricelist-angkutan.edit', str_replace('/', '|', $model->id)) . '">Edit</a></li>
                        </ul>
                        </div>';

                return $edit;
            })
            ->rawColumns(['menu', 'pemuatan', 'angkutan'])
            ->toJson();
    }

    public function create()
    {
        $kd_pat = Pat::get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $min_tahun = (int) date('Y', strtotime(date('Y') . " -5 year"));
        $max_tahun = (int) date('Y', strtotime(date('Y') . " +5 year"));
        
        $tahun = [];
        
        for ($i=$min_tahun; $i < $max_tahun; $i++) { 
            $tahun[$i] = $i;
        }
        
        $kd_material = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->pluck('name', 'kd_material')
            ->toArray();
        
        $jenis_muat =  [
            "unit" => "UnitKerja", 
            "vendor" => "Vendor Material", 
            "site" => "Site"
        ];
        
        $jenis_muat = ["" => "Pilih Jenis Pemuatan"] + $jenis_muat;

        return view('pages.pricelist-angkutan.create', compact(
            'kd_pat', 'tahun', 'kd_material', 'jenis_muat'
        ));
    }

    public function getLokasiPemuatan(Request $request)
    {
        $years = [];
        for ($i=0; $i < 3; $i++) { 
            $years[] = date('y', strtotime('-' . $i . ' years'));
        }
        $data = match ($request->jenis_muat) {
            'unit'      => Pat::get()->pluck('ket', 'kd_pat'),
            'vendor'    => Vendor::where('sync_eproc', 1)->get()->pluck('nama', 'vendor_id'),
            'site'      => Npp::whereIn(DB::raw('SUBSTR(no_npp, 1, 2)'), $years)->get()->pluck('nama_proyek', 'no_npp'),
            default     => Pat::get()->pluck('ket', 'kd_pat')
        };
        
        return $data->toArray();
    }

    public function uploadExcel(Request $request)
    {
        $array = (new PricelistImport)->toArray($request->file_excel);
        
        $html = view('pages.pricelist-angkutan.table-harsat', [
            'listData' => $array[0]
        ])->render();
        
        return response()->json(array('success' => true, 'html'=> $html));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'kd_pat'        => 'required'
            ])->validate();

            $pricelistAngkutanH = PricelistAngkutanH::firstOrNew([
                'kd_pat' => $request->kd_pat,
                'tahun'  => $request->tahun
            ]);
            $pricelistAngkutanH->save();

            $j=0;
            $countHarsat = 0;

            for($i=0; $i < count($request->kd_material); $i++){
                // $pricelistAngkutanD = PricelistAngkutanD::firstOrNew([
                //     'pah_id' => $pricelistAngkutanH->id,
                //     'kd_material'  => $request->kd_material[$i],
                //     'jenis_muat'  => $request->kd_material[$i],
                // ]);;
                // throw ValidationException::withMessages(['your error message']);
                $pricelistAngkutanD = new PricelistAngkutanD;
                $pricelistAngkutanD->pah_id = $pricelistAngkutanH->id;
                $pricelistAngkutanD->kd_material = $request->kd_material[$i];
                $pricelistAngkutanD->jenis_muat = $request->jenis_muat[$i];
                $pricelistAngkutanD->kd_muat = $request->kd_muat[$i];
                $pricelistAngkutanD->tgl_mulai = DB::raw("TO_DATE('".date('Y-m-d', strtotime($request->tgl_mulai[$i]))."', 'YYYY-MM-DD')");
                $pricelistAngkutanD->tgl_selesai = DB::raw("TO_DATE('".date('Y-m-d', strtotime($request->tgl_selesai[$i]))."', 'YYYY-MM-DD')");
                $pricelistAngkutanD->save();
                $countHarsat += $request->count_harsat[$i];

                for($j; $j < $countHarsat; $j++){
                    $pricelistAngkutanD2 = new PricelistAngkutanD2();
                    $pricelistAngkutanD2->pad_id = $pricelistAngkutanD->id;
                    $pricelistAngkutanD2->range_min = $request->range_min[$j];
                    $pricelistAngkutanD2->range_max = $request->range_max[$j];
                    $pricelistAngkutanD2->h_pusat = $request->h_pusat[$j];
                    $pricelistAngkutanD2->h_final = $request->h_final[$j];
                    $pricelistAngkutanD2->save();
                }
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('pricelist-angkutan.index');
    }

    public function edit($id)
    {
        $data = PricelistAngkutanH::find($id);

        $kd_pat = Pat::get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $min_tahun = (int) date('Y', strtotime(date('Y') . " -5 year"));
        $max_tahun = (int) date('Y', strtotime(date('Y') . " +5 year"));
        
        $tahun = [];
        
        for ($i=$min_tahun; $i < $max_tahun; $i++) { 
            $tahun[$i] = $i;
        }
        
        
        $kd_material = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->pluck('name', 'kd_material')
            ->toArray();
        
        $jenis_muat =  [
            "unit" => "UnitKerja", 
            "vendor" => "Vendor Material", 
            "site" => "Site"
        ];
        
        $jenis_muat = ["" => "Pilih Jenis Pemuatan"] + $jenis_muat;

        return view('pages.pricelist-angkutan.edit', compact(
            'data', 'kd_pat', 'tahun', 'kd_material', 'jenis_muat'
        ));
    }

    public function update(Request $request, FlasherInterface $flasher, $id)
    {
        try {
            DB::beginTransaction();
                        
            Validator::make($request->all(), [
                'kd_pat'        => 'required'
            ])->validate();

            $pricelistAngkutanH = PricelistAngkutanH::find($id);
            $pricelistAngkutanH->kd_pat = $request->kd_pat;
            $pricelistAngkutanH->tahun = $request->tahun;
            $pricelistAngkutanH->save();

            $j=0;
            $countHarsat = 0;

            // foreach ($pricelistAngkutanH->pad as $pad) {
            //     $pad->pad2()->delete();
            // }

            $pricelistAngkutanH->pad->each(function($d){ $d->pad2()->delete(); });
            $pricelistAngkutanH->pad()->delete();
            
            for($i=0; $i < count($request->kd_material); $i++){
                $pricelistAngkutanD = PricelistAngkutanD::withTrashed()->firstOrNew([
                    'pah_id'      => $pricelistAngkutanH->id,
                    'kd_material' => $request->kd_material[$i],
                    'jenis_muat'  => $request->jenis_muat[$i],
                    'kd_muat'     => $request->kd_muat[$i]
                ]);
                if($pricelistAngkutanD->id){
                    $pricelistAngkutanD->restore();
                }
                $pricelistAngkutanD->tgl_mulai = DB::raw("TO_DATE('".date('Y-m-d', strtotime($request->tgl_mulai[$i]))."', 'YYYY-MM-DD')");
                $pricelistAngkutanD->tgl_selesai = DB::raw("TO_DATE('".date('Y-m-d', strtotime($request->tgl_selesai[$i]))."', 'YYYY-MM-DD')");
                $pricelistAngkutanD->save();

                $countHarsat += $request->count_harsat[$i];

                for($j; $j < $countHarsat; $j++){
                    $pricelistAngkutanD2 = PricelistAngkutanD2::withTrashed()->firstOrNew([
                        'pad_id' => $pricelistAngkutanD->id,
                        'range_min'  => $request->range_min[$j],
                        'range_max'  => $request->range_max[$j]
                    ]);
                    if($pricelistAngkutanD2->id){
                        $pricelistAngkutanD2->restore();
                    }
                    $pricelistAngkutanD2->h_pusat = $request->h_pusat[$j];
                    $pricelistAngkutanD2->h_final = $request->h_final[$j];
                    $pricelistAngkutanD2->save();
                }
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            // dd($e);
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('pricelist-angkutan.index');
    }

    public function show($id){
        $data = PricelistAngkutanH::find($id);

        $kd_pat = Pat::get()
            ->pluck('ket', 'kd_pat')
            ->toArray();

        $min_tahun = (int) date('Y', strtotime(date('Y') . " -5 year"));
        $max_tahun = (int) date('Y', strtotime(date('Y') . " +5 year"));
        
        $tahun = [];
        
        for ($i=$min_tahun; $i < $max_tahun; $i++) { 
            $tahun[$i] = $i;
        }
        
        $kd_material = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->pluck('name', 'kd_material')
            ->toArray();
        
        $jenis_muat =  [
            "unit" => "UnitKerja", 
            "vendor" => "Vendor Material", 
            "site" => "Site"
        ];
        
        $jenis_muat = ["" => "Pilih Jenis Pemuatan"] + $jenis_muat;

        return view('pages.pricelist-angkutan.show', compact(
            'data', 'kd_pat', 'tahun', 'kd_material', 'jenis_muat'
        ));
    }
}