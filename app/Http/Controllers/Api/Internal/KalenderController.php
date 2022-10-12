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
            $data = (new KalenderService($request->start, $request->end))->rekapDailySpm();
        }else{
            $data = (new KalenderService($request->start, $request->end))->rekapDailySppWithSp3();
        }
		return response()->json($data);
    }
}

