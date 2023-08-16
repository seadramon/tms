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
                'spm' => '#12eb66'
            ];
        }elseif($request->type == 'sptb'){
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySptb($request->nopol);
            $color = [
                'sptb' => '#8fbea5',
                'spm' => '#a2bdee'
            ];
        }else{
            $temp = (new KalenderService($request->start, $request->end, $request->kd_pat))->rekapDailySppWithSp3();
            $color = [
                'spp' => '#af96e2',
                'sp3' => '#b6f5f7',
                'sptb' => '#8fbea5'
            ];
        }
		return response()->json([
            'data' => $temp,
            'color' => $color,
        ]);
    }
}

