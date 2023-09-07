<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Services\KalenderService;
use Illuminate\Http\Request;

class KalenderController extends Controller
{

    public function daily(Request $request)
    {
        if($request->type == 'spm'){
            $temp = (new KalenderService($request->start, $request->end, $request->kd_pat))->rekapDailySpm($request->nopol ?? null);
            $color = [
                'spm' => '#D6E8D4'
            ];
        }elseif($request->type == 'sptb'){
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySptb($request->nopol);
            $color = [
                'sptb' => '#DAE8FC',
                'spm' => '#D6E8D4'
            ];
        }else{
            $temp = (new KalenderService($request->start, $request->end, $request->kd_pat))->rekapDailySppWithSp3();
            $color = [
                'spp' => '#af96e2',
                'sp3' => '#b6f5f7',
                'sptb' => '#DAE8FC'
            ];
        }
		return response()->json([
            'data' => $temp,
            'color' => $color,
        ]);
    }
}

