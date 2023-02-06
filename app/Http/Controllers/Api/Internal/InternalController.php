<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Http\Resources\Internal\PelangganUserResource;
use App\Http\Resources\Internal\SpmListResource;
use App\Models\GpsLog;
use App\Models\Npp;
use App\Models\PelangganNpp;
use App\Models\PelangganUser;
use App\Models\SpmH;
use App\Models\SptbH;
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
        return response()->json([
            'success' => $code,
            'message' => $message,
            'data' => [
                'gps' => $gps,
                'ppb_muat' => $sptb->ppb_muat ?? null
            ]
        ], $code);
    }

    public function spmList(Request $request)
    {
        $query = SpmH::with('sppb.npp', 'vendor', 'pat', 'spmd.sbu')->whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($request->tgl)), date('Y-m-d 23:59:59', strtotime($request->tgl))]);
        if($request->kd_pat && $request->kd_pat != '0A'){
            $query->whereHas('sppb.npp', function($sql1) use($request) {
                $sql1->where('kd_pat', $request->kd_pat);
            });
        }
        $spm = $query->get();


        return SpmListResource::collection($spm);
    }
}

