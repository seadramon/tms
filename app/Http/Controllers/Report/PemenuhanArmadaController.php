<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Pat;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\TrMaterial;
use App\Models\Vendor;
use Flasher\Prime\FlasherInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;

class PemenuhanArmadaController extends Controller
{
    public function index(){
        $labelSemua = ["" => "Semua"];

        $kd_pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();
            
        $kd_pat = $labelSemua + $kd_pat;

        $vendor_id = Vendor::get() 
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor_id = $labelSemua + $vendor_id;
        
        $kd_material = TrMaterial::where('kd_jmaterial', 'T')
            ->get()
            ->pluck('name', 'kd_material')
            ->toArray();
        
        $kd_material = $labelSemua + $kd_material;

        $pbb_muat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['2', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();
            
        $pbb_muat = $labelSemua + $pbb_muat;

        return view('pages.report.pemenuhan-armada.index', compact(
            'kd_pat', 
            'vendor_id', 
            'kd_material', 
            'pbb_muat'
        ));
    }

    public function data(Request $request)
    {
        $query = SpmH::select('spm_h.*', 'sppb_h.no_npp as no_npp', 'tb_pat.ket as ket', 'sptb_h.no_sptb as no_sptb', 
            'sptb_h.tgl_sptb as tgl_sptb', 'vendor.nama as nama_vendor', 'tms_armadas.detail as jenis_armada')
            ->leftJoin('sppb_h', 'sppb_h.no_sppb', '=', 'spm_h.no_sppb')
            ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'spm_h.pat_to')
            ->leftJoin('sptb_h', 'sptb_h.no_spm', '=', 'spm_h.no_spm')
            ->leftJoin('vendor', 'vendor.vendor_id', '=', 'spm_h.vendor_id')
            ->leftJoin('tms_armadas', 'tms_armadas.nopol', '=', 'spm_h.no_pol');

        if($request->kd_pat){
            $query->where('pat_to', $request->kd_pat);
        }

        if($request->pbb_muat){
            $query->where('pat_to', $request->pbb_muat);
        }
        
        if($request->vendor_id){
            $query->where('spm_h.vendor_id', $request->vendor_id);
        }
        
        if($request->kd_material){
            $query->where('tms_armadas.kd_armada', $request->kd_material);
        }

        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

            $query->whereBetween('spm_h.tgl_spm', $periode);
        }

        return DataTables::eloquent($query)
            ->editColumn('tgl_spm', function ($model) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_spm)->format('d-m-Y');
            })
            ->editColumn('tgl_sptb', function ($model) {
                return $model->tgl_sptb ? Carbon::createFromFormat('Y-m-d H:i:s', $model->tgl_sptb)->format('d-m-Y') : '-';
            })
            ->toJson();
    }

    public function chart(Request $request)
    {
        $rencana = SpmH::select(DB::raw('extract(MONTH from tgl_spm) as bulan'), DB::raw('count(*) as total'))
            ->groupby(DB::raw('extract (MONTH from tgl_spm)'))
            ->leftJoin('tms_armadas', 'tms_armadas.nopol', '=', 'spm_h.no_pol');

        if($request->kd_pat){
            $rencana->where('pat_to', $request->kd_pat);
        }

        if($request->pbb_muat){
            $rencana->where('pat_to', $request->pbb_muat);
        }
        
        if($request->vendor_id){
            $rencana->where('spm_h.vendor_id', $request->vendor_id);
        }
        
        if($request->kd_material){
            $rencana->where('tms_armadas.kd_armada', $request->kd_material);
        }
        
        if($request->periode){
            $periode = explode(' - ', $request->periode);

            $periode[0] = date('Y-m-d', strtotime($periode[0]));
            $periode[1] = date('Y-m-d', strtotime($periode[1]));

            $rencana->whereBetween('spm_h.tgl_spm', $periode);
        }

        $rencana = $rencana->get();

        $realisasi = SptbH::select(DB::raw('extract(MONTH from tgl_sptb) as bulan'), DB::raw('count(*) as total'))
            ->groupby(DB::raw('extract (MONTH from tgl_sptb)'))
            ->whereHas('spmh', function($query) use ($request){
                $query->leftJoin('tms_armadas', 'tms_armadas.nopol', '=', 'spm_h.no_pol');
                
                if($request->kd_pat){
                    $query->where('pat_to', $request->kd_pat);
                }
        
                if($request->pbb_muat){
                    $query->where('pat_to', $request->pbb_muat);
                }
                
                if($request->vendor_id){
                    $query->where('spm_h.vendor_id', $request->vendor_id);
                }
                
                if($request->kd_material){
                    $query->where('tms_armadas.kd_armada', $request->kd_material);
                }
                
                if($request->periode){
                    $periode = explode(' - ', $request->periode);
        
                    $periode[0] = date('Y-m-d', strtotime($periode[0]));
                    $periode[1] = date('Y-m-d', strtotime($periode[1]));
        
                    $query->whereBetween('spm_h.tgl_spm', $periode);
                }
            })->get();

        return [
            'rencana'   => $rencana,
            'realisasi' => $realisasi
        ];
    }
}