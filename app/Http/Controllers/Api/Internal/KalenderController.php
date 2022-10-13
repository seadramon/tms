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
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySpm();
            $color = null;
        }else{
            $temp = (new KalenderService($request->start, $request->end))->rekapDailySppWithSp3();
            $color = [
                'spp' => '#af96e2',
                'sp3' => '#b6f5f7'
            ];
        }
		return response()->json([
            'data' => $temp,
            'color' => $color,
        ]);
    }
}

