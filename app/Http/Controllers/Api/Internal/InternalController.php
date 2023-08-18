<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Http\Resources\Internal\PelangganUserResource;
use App\Http\Resources\Internal\SpmListResource;
use App\Http\Resources\Internal\SptbListResource;
use App\Models\GpsLog;
use App\Models\Npp;
use App\Models\PelangganNpp;
use App\Models\PelangganUser;
use App\Models\PotensiH;
use App\Models\SpmH;
use App\Models\SptbH;
use App\Models\Views\VSpprbRi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalController extends Controller
{
    public function gpsTracker(Request $request)
    {
        $code = 200;
        $message = "Success";
        $gps = GpsLog::whereNoSptb($request->no_sptb)->orderByDesc('created_at')->first();
        $sptb = SptbH::whereNoSptb($request->no_sptb)->first();
        $potensi = PotensiH::where('no_npp', $sptb->no_npp)->where('pat_to', $sptb->kd_pat)->first();
        if($potensi){
            $rute = [
                'source_lat'  => $potensi->source_lat,
                'source_long' => $potensi->source_long,
                'dest_lat'    => $potensi->dest_lat,
                'dest_long'   => $potensi->dest_long,
                'checkpoints' => $potensi->checkpoints
            ];
        } else {
            $spprbRi = VSpprbRi::select('kd_produk','pat_to','no_npp','vol_spprb')
                        ->with(['produk' => function($sql){
                            $sql->select('kd_produk','tipe', 'vol_m3');
                        }])
                        ->with(['ppb_muat' =>function($sql){
                            $sql->select('kd_pat','lat_gps','lng_gps');
                        }])
                        ->where('no_npp',$sptb->no_npp)
                        ->where('pat_to',$sptb->kd_pat)
                        ->groupBy('kd_produk','pat_to','no_npp','vol_spprb')
                        ->get();

            $sqlNpp = Npp::select('npp.nama_proyek',
                        'npp.nama_pelanggan',
                        'npp.no_npp',
                        'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                        'tb_pat.ket as pat',
                        'npp.kd_pat',
                        'tb_pat.singkatan',
                        'info_pasar_h.lat as info_pasar_lat','info_pasar_h.lang as info_pasar_long',
                        'tb_region.lat as tb_region_lat','tb_region.lang as tb_region_long')
                    ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                    ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                    ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                    ->where('npp.no_npp', $sptb->no_npp)
                    ->first();
            $rute = [
                'source_lat'  => $spprbRi[0]->ppb_muat->lat_gps,
                'source_long' => $spprbRi[0]->ppb_muat->lng_gps,
                'dest_lat'    => $sqlNpp->info_pasar_lat ?? $sqlNpp->tb_region_lat,
                'dest_long'   => $sqlNpp->info_pasar_long ?? $sqlNpp->tb_region_long,
                'checkpoints' => []
            ];
        }
        
        return response()->json([
            'success' => $code,
            'message' => $message,
            'data' => [
                'gps' => $gps,
                'ppb_muat' => $sptb->ppb_muat ?? null,
                'rute' => $rute
            ]
        ], $code);
    }

    public function spmList(Request $request)
    {
        $query = SpmH::with('sppb.npp', 'vendor', 'pat', 'spmd.sbu', 'sptbh')->whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($request->tgl)), date('Y-m-d 23:59:59', strtotime($request->tgl))]);
        if($request->kd_pat && $request->kd_pat != '0A'){
            $query->whereHas('sppb.npp', function($sql1) use($request) {
                $sql1->where('kd_pat', $request->kd_pat);
            });
        }
        $spm = $query->get();


        return SpmListResource::collection($spm);
    }
    
    public function sptbList(Request $request)
    {
        $query = SptbH::with('npp', 'spmh', 'ppb_muat')
            ->with(['sptbd' => function($sql){
                $sql->leftJoin('tb_sbu', DB::raw('substr(sptb_d.kd_produk,1,1)'), '=', 'tb_sbu.kd_sbu');
            }]);
        if($request->no_npp){
            $query->whereNoNpp($request->no_npp);
        }
        if($request->tgl_awal && $request->tgl_akhir){
            $query->whereBetween('tgl_berangkat', [date('Y-m-d 00:00:00', strtotime($request->tgl_awal)), date('Y-m-d 23:59:59', strtotime($request->tgl_akhir))]);
        }
        $sptb = $query->orderByRaw('app_pelanggan ASC NULLS FIRST')->orderBy('tgl_berangkat', 'asc')->get();


        return SptbListResource::collection($sptb);
    }
}

