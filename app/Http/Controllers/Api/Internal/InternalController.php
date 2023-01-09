<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\NppResource;
use App\Http\Resources\Internal\PelangganUserResource;
use App\Models\GpsLog;
use App\Models\Npp;
use App\Models\PelangganNpp;
use App\Models\PelangganUser;
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
        return response()->json([
            'success' => $code,
            'message' => $message,
            'data' => $gps
        ], $code);
    }
}

