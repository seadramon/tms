<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\TrMaterial;
use App\Models\Vendor;
use App\Models\Npp;
use App\Models\AngkutanH;
use App\Models\AngkutanD;
use App\Models\AngkutanD2;
use App\Imports\PricelistImport;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;

class PricelistAngkutanController extends Controller
{   
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
            ->pluck('uraian', 'kd_jmaterial')
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
        $data = match ($request->jenis_muat) {
            'unit'      => Pat::get()->pluck('ket', 'kd_pat'),
            'vendor'    => Vendor::get()->pluck('nama', 'vendor_id'),
            'site'      => Npp::get()->pluck('nama_proyek', 'no_npp')
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

            $angkutanH = new AngkutanH();
            $angkutanH->kd_pat = $request->kd_pat;
            $angkutanH->tahun = $request->tahun;
            $angkutanH->save();

            for($i=0; $i < count($request->kd_material); $i++){
                $angkutanD = new AngkutanD();
                $angkutanD->kd_material = $request->kd_material[$i];
                $angkutanD->jenis_muat = $request->jenis_muat[$i];
                $angkutanD->kd_muat = $request->kd_muat[$i];
                $angkutanD->tgl_mulai = Carbon::createFromFormat('d-m-Y', $request->tgl_mulai[$i])->format('Y-m-d');
                $angkutanD->tgl_selesai = Carbon::createFromFormat('d-m-Y', $request->tgl_selesai[$i])->format('Y-m-d');
                $angkutanD->save();
            }

            for($i=0; $i < count($request->key_harsat); $i++){
                $angkutanD2 = new AngkutanD2();
                $angkutanD2->range_min = $request->range_min[$i];
                $angkutanD2->range_max = $request->range_max[$i];
                $angkutanD2->h_pusat = $request->h_pusat[$i];
                $angkutanD2->h_final = $request->h_final[$i];
                $angkutanD2->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            dd($e);
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('pricelist-angkutan.create');
    }
}